<?php

namespace MediaAlchemyst\Transmuter;

use FFMpeg\Exception\ExceptionInterface as FFMpegException;
use Imagine\Exception\Exception as ImagineException;
use MediaVorus\Exception\ExceptionInterface as MediaVorusException;
use MediaAlchemyst\Specification\SpecificationInterface;
use MediaAlchemyst\Specification\Image;
use Imagine\Image\ImageInterface;
use MediaAlchemyst\Exception\RuntimeException;
use MediaAlchemyst\Exception\SpecNotSupportedException;
use MediaVorus\Media\MediaInterface;

class Video2Image extends AbstractTransmuter
{
    public static $time = '60%';

    public function execute(SpecificationInterface $spec, MediaInterface $source, $dest)
    {
        if ( ! $spec instanceof Image) {
            throw new SpecNotSupportedException('FFMpeg Adapter only supports Video specs');
        }

        /* @var $spec \MediaAlchemyst\Specification\Image */

        $tmpDest = tempnam(sys_get_temp_dir(), 'ffmpeg') . '.jpg';

        $time = (int) ($source->getDuration() * $this->parseTimeAsRatio(static::$time));

        try {
            $this->container['ffmpeg.ffmpeg']
                ->open($source->getFile()->getPathname())
                ->extractImage($time, $tmpDest)
                ->close();

            $image = $this->container['imagine']->open($tmpDest);

            if ($spec->getWidth() && $spec->getHeight()) {

                $media = $this->container['mediavorus']->guess($tmpDest);

                $box = $this->boxFromImageSpec($spec, $source);

                if ($spec->getResizeMode() == Image::RESIZE_MODE_OUTBOUND) {
                    /* @var $image \Imagine\Gmagick\Image */
                    $image = $image->thumbnail($box, ImageInterface::THUMBNAIL_OUTBOUND);
                } else {
                    $image = $image->resize($box);
                }

                unset($media);
            }

            $options = array(
                'quality'          => $spec->getQuality(),
                'resolution-units' => $spec->getResolutionUnit(),
                'resolution-x'     => $spec->getResolutionX(),
                'resolution-y'     => $spec->getResolutionY(),
            );

            $image->save($dest, $options);

            $image = null;
            unlink($tmpDest);
        } catch (FFMpegException $e) {
            throw new RuntimeException('Unable to transmute video to image due to FFMpeg', null, $e);
        } catch (ImagineException $e) {
            throw new RuntimeException('Unable to transmute video to image due to Imagine', null, $e);
        } catch (MediaVorusException $e) {
            throw new RuntimeException('Unable to transmute video to image due to Mediavorus', null, $e);
        }
    }

    protected function parseTimeAsRatio($time)
    {
        if (substr($time, -1) === '%') {
            return substr($time, 0, strlen($time) - 1) / 100;
        }

        return Max(Min((float) $time, 1), 0);
    }
}
