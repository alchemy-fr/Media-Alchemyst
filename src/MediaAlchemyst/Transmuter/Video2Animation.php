<?php

namespace MediaAlchemyst\Transmuter;

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

        if ( ! class_exists('\\Gmagick')) {
            throw new Exception\RuntimeException('Gmagick is required for animated gif');
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
            $movie = null;

            $GIF = new \Gmagick();

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
        } catch (\FFMpeg\Exception\Exception $e) {
            throw new Exception\RuntimeException($e->getMessage(), $e->getCode(), $e);
        } catch (\GmagickException $e) {
            throw new Exception\RuntimeException($e->getMessage(), $e->getCode(), $e);
        }

        return $this;
    }
}
