<?php

namespace MediaAlchemyst\Transmuter;

use FFMpeg\Exception\ExceptionInterface as FFMpegException;
use Imagine\Image\ImageInterface;
use Gmagick;
use GmagickException;
use MediaAlchemyst\Specification\Animation;
use MediaAlchemyst\Specification\SpecificationInterface;
use MediaAlchemyst\Exception\SpecNotSupportedException;
use MediaAlchemyst\Exception\RuntimeException;
use MediaVorus\Media\MediaInterface;

class Video2Animation extends AbstractTransmuter
{
    public static $autorotate = false;
    public static $lookForEmbeddedPreview = false;

    public function execute(SpecificationInterface $spec, MediaInterface $source, $dest)
    {
        if ( ! $spec instanceof Animation) {
            throw new SpecNotSupportedException('Imagine Adapter only supports Image specs');
        }

        if ($source->getType() !== MediaInterface::TYPE_VIDEO) {
            throw new SpecNotSupportedException('Imagine Adapter only supports Images');
        }

        if ( ! class_exists('\\Gmagick')) {
            throw new RuntimeException('Gmagick is required for animated gif');
        }

        try {
            $movie = $this->container['ffmpeg.ffmpeg']->open($source->getFile()->getPathname());

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

                $image = $this->container['imagine']->open($file);

                if ($spec->getWidth() && $spec->getHeight()) {

                    $box = $this->boxFromImageSpec($spec, $this->container['mediavorus']->guess($file));

                    if ($spec->getResizeMode() == Animation::RESIZE_MODE_OUTBOUND) {
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

            $GIF = new Gmagick();

            $GIF->readImage(array_shift($files));
            $GIF->setImageFormat('GIF');
            $GIF->setImageDelay(80);
            $GIF->nextImage();

            foreach ($files as $file) {
                $frame = new \Gmagick();

                $frame->readImage($file);
                $frame->setImageFormat('GIF');
                $frame->setImageDelay(80);

                $GIF->addImage($frame);
                $GIF->nextImage();

                $frame->clear();
                $frame->destroy();
                $frame = null;
            }

            $GIF->writeimage($dest);
            $GIF->clear();
            $GIF->destroy();

            $GIF = null;
        } catch (FFMpegException $e) {
            throw new RuntimeException('Unable to transmute video to animation due to FFMpeg', null, $e);
        } catch (GmagickException $e) {
            throw new RuntimeException('Unable to transmute video to animation due to Gmagick', null, $e);
        }

        return $this;
    }
}
