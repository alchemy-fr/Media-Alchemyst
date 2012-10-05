<?php

namespace MediaAlchemyst;

use Monolog\Logger;
use Monolog\Handler\NullHandler;
use MediaAlchemyst\Driver\ExiftoolExtractor;
use MediaAlchemyst\Driver\FFMpeg;
use MediaAlchemyst\Driver\Imagine;
use MediaAlchemyst\Driver\MP4Box;
use MediaAlchemyst\Driver\MediaVorus;
use MediaAlchemyst\Driver\Pdf2Swf;
use MediaAlchemyst\Driver\SwfToolsFlashFile;
use MediaAlchemyst\Driver\SwfToolsPDFFile;
use MediaAlchemyst\Driver\Unoconv;
use Pimple;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBag;

class DriversContainer extends Pimple
{

    public function __construct(ParameterBag $configuration, Logger $logger = null)
    {
        if ( ! $logger) {
            $logger = new Logger('Drivers');
            $logger->pushHandler(new NullHandler());
        }

        $this['ffmpeg.ffmpeg'] = $this->share(function() use ($configuration, $logger) {
                $ffmpeg = $configuration->has('ffmpeg') ? $configuration->get('ffmpeg') : null;
                $ffprobe = $configuration->has('ffprobe') ? $configuration->get('ffprobe') : null;
                $threads = $configuration->has('ffmpeg.threads') ? $configuration->get('ffmpeg.threads') : 1;

                $driver = new FFMpeg($logger, $ffmpeg, $ffprobe, $threads);

                return $driver->getDriver();
            });

        $this['imagine'] = $this->share(function() use ($configuration, $logger) {
                $imagine = $configuration->has('imagine') ? $configuration->get('imagine') : null;

                $driver = new Imagine($logger, $imagine);

                return $driver->getDriver();
            });

        $this['swftools.flash-file'] = $this->share(function() use ($configuration, $logger) {
                $SwfRender = $configuration->has('SwfRender') ? $configuration->get('SwfRender') : null;
                $SwfExtract = $configuration->has('SwfExtract') ? $configuration->get('SwfExtract') : null;

                $driver = new SwfToolsFlashFile($logger, $SwfExtract, $SwfRender);

                return $driver->getDriver();
            });

        $this['swftools.pdf-file'] = $this->share(function() use ($configuration, $logger) {
                $pdf2swf = $configuration->has('Pdf2Swf') ? $configuration->get('Pdf2Swf') : null;

                $driver = new SwfToolsPDFFile($logger, $pdf2swf);

                return $driver->getDriver();
            });

        $this['unoconv'] = $this->share(function() use ($configuration, $logger) {
                $unoconv = $configuration->has('Unoconv') ? $configuration->get('Unoconv') : null;

                $driver = new Unoconv($logger, $unoconv);

                return $driver->getDriver();
            });

        $this['exiftool.preview-extractor'] = $this->share(function() use ($configuration, $logger) {
                $driver = new ExiftoolExtractor($logger, null);

                return $driver->getDriver();
            });

        $this['mp4box'] = $this->share(function() use ($configuration, $logger) {
                $MP4Box = $configuration->has('MP4Box') ? $configuration->get('MP4Box') : null;

                $driver = new MP4Box($logger, $MP4Box);

                return $driver->getDriver();
            });

        $this['mediavorus'] = $this->share(function() use ($configuration, $logger) {

                $ffprobeConf = $configuration->has('ffprobe') ? $configuration->get('ffprobe') : null;

                $driver = new MediaVorus($logger, $ffprobeConf);

                return $driver->getDriver();
            });
    }

    public static function create()
    {
        return new static(new ParameterBag());
    }

}
