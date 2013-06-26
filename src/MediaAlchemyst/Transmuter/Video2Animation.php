<?php

namespace MediaAlchemyst\Transmuter;

use FFMpeg\Exception\ExceptionInterface as FFMpegException;
use Imagine\Image\ImageInterface;
use Imagine\Exception\Exception as ImagineException;
use MediaAlchemyst\Specification\Animation;
use MediaAlchemyst\Specification\SpecificationInterface;
use MediaAlchemyst\Exception\SpecNotSupportedException;
use MediaAlchemyst\Exception\RuntimeException;
use MediaVorus\Media\MediaInterface;
use FFMpeg\Coordinate\TimeCode;

class Video2Animation extends AbstractTransmuter
{
    public static $autorotate = false;
    public static $lookForEmbeddedPreview = false;

    public function execute(SpecificationInterface $spec, MediaInterface $source, $dest)
    {
        if (! $spec instanceof Animation) {
            throw new SpecNotSupportedException('Imagine Adapter only supports Image specs');
        }

        if ($source->getType() !== MediaInterface::TYPE_VIDEO) {
            throw new SpecNotSupportedException('Imagine Adapter only supports Images');
        }

        try {
            $movie = $this->container['ffmpeg.ffmpeg']
                ->open($source->getFile()->getPathname());

            $duration = $source->getDuration();

            $time = $pas = Max(1, $duration / 11);
            $files = array();

            while (ceil($time) < floor($duration)) {
                $files[] = $tmpFile = tempnam(sys_get_temp_dir(), 'ffmpeg') . '.jpg';
                $movie->frame(TimeCode::fromSeconds($time))->saveAs($tmpFile);
                $time += $pas;
            }

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

            $image = $this->container['imagine']->open(array_shift($files));

            foreach ($files as $file) {
                $image->layers()->add($this->container['imagine']->open($file));
            }

            $image->save($dest, array(
                'animated' => true,
                'animated.delay' => 800,
                'animated.loops' => 0,
            ));
        } catch (FFMpegException $e) {
            throw new RuntimeException('Unable to transmute video to animation due to FFMpeg', null, $e);
        } catch (ImagineException $e) {
            throw new RuntimeException('Unable to transmute video to animation due to Imagine', null, $e);
        }

        return $this;
    }
}
