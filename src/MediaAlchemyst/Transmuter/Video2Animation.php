<?php

namespace MediaAlchemyst\Transmuter;

use Imagine\Image\ImageInterface;
use MediaAlchemyst\Specification;
use MediaAlchemyst\Exception;
use MediaVorus\Media\Media;
use Imagine\Image;

class Video2Animation extends Provider
{
    public static $autorotate = false;
    public static $lookForEmbeddedPreview = false;

    public function execute(Specification\Provider $spec, Media $source, $dest)
    {
        if ( ! $spec instanceof Specification\Animation) {
            throw new Exception\SpecNotSupportedException('Imagine Adapter only supports Image specs');
        }

        if ($source->getType() !== Media::TYPE_VIDEO) {
            throw new Exception\SpecNotSupportedException('Imagine Adapter only supports Images');
        }

        try {
            $movie = $this->container->getFFMpeg()->open($source->getFile()->getPathname());

            $duration = $source->getDuration();

            $time = $pas = Max(1, $duration / 11);
            $files = array();

            while (ceil($time) < floor($duration)) {
                $files[] = $tmpFile = tempnam(sys_get_temp_dir(), 'ffmpeg') . '.jpg';
                $movie->extractImage(round($time), $tmpFile);
                $time += $pas;
            }

            $movie->close();
            unset($movie);

            foreach ($files as $file) {

                $image = $this->container->getImagine()->open($file);

                if ($spec->getWidth() && $spec->getHeight()) {

                    $box = $this->boxFromImageSpec($spec, $this->container['mediavorus']->guess(new \SplFileInfo($file)));

                    if ($spec->getResizeMode() == Specification\Animation::RESIZE_MODE_OUTBOUND) {
                        /* @var $image \Imagine\Gmagick\Image */
                        $image = $image->thumbnail($box, ImageInterface::THUMBNAIL_OUTBOUND);
                    } else {
                        $image = $image->resize($box);
                    }
                }

                $image->save($file, array(
                    'quality'          => $spec->getQuality(),
                    'resolution-units' => $spec->getResolutionUnit(),
                    'resolution-x'     => $spec->getResolutionX(),
                    'resolution-y'     => $spec->getResolutionY(),
                ));

                unset($image);
            }

            $image = $this->container->getImagine()->open(array_shift($files));

            foreach ($files as $file) {
                $image->layers()->add($this->container->getImagine()->open($file));
            }

            $image->save($dest, array(
                'animated' => true,
                'animated.delay' => 800,
                'animated.loops' => 0,
            ));
        } catch (\FFMpeg\Exception\Exception $e) {
            throw new Exception\RuntimeException($e->getMessage(), $e->getCode(), $e);
        } catch (\Imagine\Exception\Exception $e) {
            throw new Exception\RuntimeException($e->getMessage(), $e->getCode(), $e);
        }

        return $this;
    }
}
