<?php

namespace MediaAlchemyst\Transmuter;

use MediaAlchemyst\Specification;
use MediaAlchemyst\Exception;
use MediaVorus\Media\Media;

class Audio2Audio extends Provider
{

    public function execute(Specification\Provider $spec, Media $source, $dest)
    {
        if ( ! $spec instanceof Specification\Audio)
        {
            throw new Exception\SpecNotSupportedException('FFMpeg Adapter only supports Audio specs');
        }

        /* @var $spec \MediaAlchemyst\Specification\Audio */
        $format = $this->getFormatFromFileType($dest);

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

    protected function getFormatFromFileType($dest)
    {
        $extension = strtolower(pathinfo($dest, PATHINFO_EXTENSION));

        switch ($extension)
        {
            case 'flac':
                $format = new \FFMpeg\Format\Audio\Flac();
                break;
            case 'mp3':
                $format = new \FFMpeg\Format\Audio\Mp3();
                break;
            default:
                throw new Exception\FormatNotSupportedException(sprintf('Unsupported %s format', $extension));
                break;
        }

        return $format;
    }

}
