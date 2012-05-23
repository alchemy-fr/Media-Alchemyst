<?php

namespace MediaAlchemyst\Transmuter;

use MediaAlchemyst\Specification;
use MediaAlchemyst\Exception;
use MediaVorus\Media\Media;

class Video2Image extends Provider
{

    public static $time = '60%';

    public function execute(Specification\Provider $spec, Media $source, $dest)
    {
        if ( ! $spec instanceof Specification\Image)
        {
            throw new Exception\SpecNotSupportedException('FFMpeg Adapter only supports Video specs');
        }

        /* @var $spec \MediaAlchemyst\Specification\Image */

        $tmpDest = tempnam(sys_get_temp_dir(), 'ffmpeg') . '.jpg';

        $time = (int) ($source->getDuration() * $this->parseTimeAsRatio(static::$time));

        try
        {
            $this->container->getFFMpeg()
              ->open($source->getFile()->getPathname())
              ->extractImage($time, $tmpDest)
              ->close();

            $image = $this->container->getImagine()->open($tmpDest);

            if ($spec->getWidth() && $spec->getHeight())
            {
                $box   = new \Imagine\Image\Box($spec->getWidth(), $spec->getHeight());
                $image = $image->resize($box);
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
        }
        catch (\FFMpeg\Exception\Exception $e)
        {
            throw new Exception\RuntimeException($e->getMessage(), $e->getCode(), $e);
        }
        catch (\Imagine\Exception\Exception $e)
        {
            throw new Exception\RuntimeException($e->getMessage(), $e->getCode(), $e);
        }
    }

    protected function parseTimeAsRatio($time)
    {
        if (substr($time, -1) === '%')
        {
            return substr($time, 0, strlen($time) - 1) / 100;
        }

        return Max(Min((float) $time, 1), 0);
    }

}
