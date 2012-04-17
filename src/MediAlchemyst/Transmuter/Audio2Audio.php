<?php

namespace MediAlchemyst\Transmuter;

use MediAlchemyst\Specification;
use MediAlchemyst\Exception;
use MediaVorus\Media\Media;

class Audio2Audio extends Provider
{

    public function execute(Specification\Provider $spec, Media $source, $dest)
    {
        if ( ! $spec instanceof Specification\Audio)
        {
            throw new Exception\SpecNotSupportedException('FFMpeg Adapter only supports Audio specs');
        }

        /* @var $spec \MediAlchemyst\Specification\Audio */

        if ($spec->getFileType() == Specification\Audio::FILETYPE_FLAC)
        {
            $format = new \FFMpeg\Format\Audio\Flac();
        }
        elseif ($spec->getFileType() == Specification\Audio::FILETYPE_MP3)
        {
            $format = new \FFMpeg\Format\Audio\Mp3();
        }
        else
        {
            throw new Exception\FormatNotSupportedException(sprintf('Unsupported %s format', $format));
        }

        if ($spec->getAudioCodec())
        {
            $format->setAudioCodec($spec->getAudioCodec());
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

}
