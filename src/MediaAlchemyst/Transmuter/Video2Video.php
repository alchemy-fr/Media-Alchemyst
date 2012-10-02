<?php

namespace MediaAlchemyst\Transmuter;

use FFMpeg\Format\Video\X264;
use FFMpeg\Format\Video\WebM;
use FFMpeg\Format\Video\Ogg;
use FFMpeg\Format\Video\DefaultVideo;
use FFMpeg\Exception\ExceptionInterface as FFMpegException;
use MP4Box\Exception\ExceptionInterface as MP4BoxException;
use MediaAlchemyst\Specification\SpecificationInterface;
use MediaAlchemyst\Specification\Video;
use MediaAlchemyst\Exception\RuntimeException;
use MediaAlchemyst\Exception\SpecNotSupportedException;
use MediaAlchemyst\Exception\FormatNotSupportedException;
use MediaVorus\Media\MediaInterface;

class Video2Video extends AbstractTransmuter
{

    public function execute(SpecificationInterface $spec, MediaInterface $source, $dest)
    {
        if ( ! $spec instanceof Video) {
            throw new SpecNotSupportedException('FFMpeg Adapter only supports Video specs');
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
            $this->container['ffmpeg.ffmpeg']
                ->open($source->getFile()->getPathname())
                ->encode($format, $dest)
                ->close();

            if ($format instanceof X264) {
                $this->container['mp4box']
                    ->open($dest)
                    ->process()
                    ->close();
            }
        } catch (FFMpegException $e) {
            throw new RuntimeException('Unable to transmute video to video due to FFMpeg', null, $e);
        } catch (MP4BoxException $e) {
            throw new RuntimeException('Unable to transmute video to video due to MP4Box', null, $e);
        }

        return $this;
    }

    /**
     *
     * @param  string                                $dest
     * @param  integer                                   $width
     * @param  integer                                   $height
     *
     * @return DefaultVideo
     *
     * @throws FormatNotSupportedException
     */
    protected function getFormatFromFileType($dest, $width, $height)
    {
        $extension = strtolower(pathinfo($dest, PATHINFO_EXTENSION));

        switch ($extension) {
            case 'webm':
                $format = new WebM($width, $height);
                break;
            case 'mp4':
                $format = new X264($width, $height);
                break;
            case 'ogv':
                $format = new Ogg($width, $height);
                break;
            default:
                throw new FormatNotSupportedException(sprintf('Unsupported %s format', $extension));
                break;
        }

        $format->setDimensions($width, $height);

        return $format;
    }
}
