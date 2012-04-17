<?php

namespace MediAlchemyst\Transmuter;

use MediAlchemyst\Specification;
use MediAlchemyst\Exception;
use MediaVorus\Media\Media;

class Video2Video extends Provider
{

    public function execute(Specification\Provider $spec, Media $source, $dest)
    {
        if ( ! $spec instanceof Specification\Video)
        {
            throw new Exception\SpecNotSupportedException('FFMpeg Adapter only supports Video specs');
        }

        /* @var $spec \MediAlchemyst\Specification\Video */
        $format = $this->getFormatFromFileType($spec->getFileType(), $spec->getWidth(), $spec->getHeight());

        if ($spec->getAudioCodec())
        {
            $format->setAudioCodec($spec->getAudioCodec());
        }
        if ($spec->getVideoCodec())
        {
            $format->setVideoCodec($spec->getVideoCodec());
        }
        if ($spec->getAudioSampleRate())
        {
            $format->setAudioSampleRate($spec->getAudioSampleRate());
        }
        if ($spec->getKiloBitrate())
        {
            $format->getKiloBitrate($spec->getKiloBitrate());
        }

        $this->container->getFFMpeg()
          ->open($source->getFile()->getPathname())
          ->encode($format, $dest)
          ->close();
    }

    protected function getFormatFromFileType($fileType, $width, $height)
    {
        switch ($fileType)
        {
            case Specification\Video::FILETYPE_X264:
                $format = new \FFMpeg\Format\Video\X264($width, $height);
                break;
            case Specification\Video::FILETYPE_WEBM:
                $format = new \FFMpeg\Format\Video\WebM($width, $height);
                break;
            case Specification\Video::FILETYPE_OGG:
                $format = new \FFMpeg\Format\Video\Ogg($width, $height);
                break;
            default:
                throw new Exception\FormatNotSupportedException(sprintf('Unsupported %s format', $fileType));
                break;
        }

        return $format;
    }

}
