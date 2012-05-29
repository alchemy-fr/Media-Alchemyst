<?php

namespace MediaAlchemyst\Transmuter;

use MediaAlchemyst\Specification;
use MediaAlchemyst\Exception;
use MediaVorus\Media\Media;

class Video2Video extends Provider
{

    public function execute(Specification\Provider $spec, Media $source, $dest)
    {
        if ( ! $spec instanceof Specification\Video) {
            throw new Exception\SpecNotSupportedException('FFMpeg Adapter only supports Video specs');
        }

        /* @var $spec \MediaAlchemyst\Specification\Video */
        $format = $this->getFormatFromFileType($dest, $spec->getWidth(), $spec->getHeight());

        if ($spec->getAudioCodec()) {
            $format->setAudioCodec($spec->getAudioCodec());
        }
        if ($spec->getVideoCodec()) {
            $format->setVideoCodec($spec->getVideoCodec());
        }
        if ($spec->getAudioSampleRate()) {
            $format->setAudioSampleRate($spec->getAudioSampleRate());
        }
        if ($spec->getKiloBitrate()) {
            $format->getKiloBitrate($spec->getKiloBitrate());
        }
        if ($spec->getGOPSize()) {
            $format->setGOPsize($spec->getGOPSize());
        }
        if ($spec->getFramerate()) {
            $format->setFrameRate($spec->getFramerate());
        }
        if ($spec->getResizeMode()) {
            $format->setResizeMode($spec->getResizeMode());
        }

        try {
            $this->container->getFFMpeg()
              ->open($source->getFile()->getPathname())
              ->encode($format, $dest)
              ->close();

            if ($format instanceof \FFMpeg\Format\Video\X264) {
                $this->container->getMP4Box()->open($dest)->process()->close();
            }
        } catch (\FFMpeg\Exception\Exception $e) {
            throw new Exception\RuntimeException($e->getMessage(), $e->getCode(), $e);
        } catch (\MP4Box\Exception\Exception $e) {
            throw new Exception\RuntimeException($e->getMessage(), $e->getCode(), $e);
        }

        return $this;
    }

    /**
     *
     * @param  string                                $dest
     * @param  int                                   $width
     * @param  int                                   $height
     * @return \FFMpeg\Format\Video\VideoFormat
     * @throws Exception\FormatNotSupportedException
     */
    protected function getFormatFromFileType($dest, $width, $height)
    {
        $extension = strtolower(pathinfo($dest, PATHINFO_EXTENSION));

        switch ($extension) {
            case 'webm':
                $format = new \FFMpeg\Format\Video\WebM($width, $height);
                break;
            case 'mp4':
                $format = new \FFMpeg\Format\Video\X264($width, $height);
                break;
            case 'ogv':
                $format = new \FFMpeg\Format\Video\Ogg($width, $height);
                break;
            default:
                throw new Exception\FormatNotSupportedException(sprintf('Unsupported %s format', $extension));
                break;
        }

        $format->setDimensions($width, $height);

        return $format;
    }

}
