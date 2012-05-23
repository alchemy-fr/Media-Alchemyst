<?php

namespace MediaAlchemyst\Transmuter;

use MediaAlchemyst\Specification;
use MediaAlchemyst\Exception;
use MediaVorus\Media\Media;

class Audio2Audio extends Provider
{

    public function execute(Specification\Provider $spec, Media $source, $dest)
    {
        if ( ! $spec instanceof Specification\Audio) {
            throw new Exception\SpecNotSupportedException('FFMpeg Adapter only supports Audio specs');
        }

        /* @var $spec \MediaAlchemyst\Specification\Audio */
        $format = $this->getFormatFromFileType($dest);

        if ($spec->getAudioCodec()) {
            $format->setAudioCodec($spec->getAudioCodec());
        }
        if ($spec->getAudioSampleRate()) {
            $format->setAudioSampleRate($spec->getAudioSampleRate());
        }
        if ($spec->getKiloBitrate()) {
            $format->getKiloBitrate($spec->getKiloBitrate());
        }

        try {
            $this->container->getFFMpeg()
              ->open($source->getFile()->getPathname())
              ->encode($format, $dest)
              ->close();
        } catch (\FFMpeg\Exception\Exception $e) {
            throw new Exception\RuntimeException($e->getMessage(), $e->getCode(), $e);
        }
    }

    protected function getFormatFromFileType($dest)
    {
        $extension = strtolower(pathinfo($dest, PATHINFO_EXTENSION));

        switch ($extension) {
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
