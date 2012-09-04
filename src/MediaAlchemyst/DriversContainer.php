<?php

namespace MediaAlchemyst;

use MediaAlchemyst\Driver;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBag;

class DriversContainer extends \Pimple
{

    public function __construct(ParameterBag $configuration, \Monolog\Logger $logger = null)
    {
        if ( ! $logger) {
            $logger = new \Monolog\Logger('Drivers');
            $logger->pushHandler(new \Monolog\Handler\NullHandler());
        }

        $this['ffmpeg.ffmpeg'] = $this->share(function() use ($configuration, $logger) {
                $ffmpeg = $configuration->has('ffmpeg') ? $configuration->get('ffmpeg') : null;
                $ffprobe = $configuration->has('ffprobe') ? $configuration->get('ffprobe') : null;
                $threads = $configuration->has('ffmpeg.threads') ? $configuration->get('ffmpeg.threads') : 1;

                $driver = new Driver\FFMpeg($logger, $ffmpeg, $ffprobe, $threads);

                return $driver->getDriver();
            });

        $this['imagine'] = $this->share(function() use ($configuration, $logger) {
                $imagine = $configuration->has('imagine') ? $configuration->get('imagine') : null;

                $driver = new Driver\Imagine($logger, $imagine);

                return $driver->getDriver();
            });

        $this['swftools.flash-file'] = $this->share(function() use ($configuration, $logger) {
                $SwfRender = $configuration->has('SwfRender') ? $configuration->get('SwfRender') : null;
                $SwfExtract = $configuration->has('SwfExtract') ? $configuration->get('SwfExtract') : null;

                $driver = new Driver\SwfToolsFlashFile($logger, $SwfExtract, $SwfRender);

                return $driver->getDriver();
            });

        $this['swftools.pdf-file'] = $this->share(function() use ($configuration, $logger) {
                $pdf2swf = $configuration->has('Pdf2Swf') ? $configuration->get('Pdf2Swf') : null;

                $driver = new Driver\SwfToolsPDFFile($logger, $pdf2swf);

                return $driver->getDriver();
            });

        $this['xpdf.pdf2swf'] = $this->share(function() use ($configuration, $logger) {
                $SwfRender = $configuration->has('Pdf2Swf') ? $configuration->get('Pdf2Swf') : null;

                $driver = new Driver\Pdf2Swf($logger, $SwfRender);

                return $driver->getDriver();
            });

        $this['unoconv'] = $this->share(function() use ($configuration, $logger) {
                $unoconv = $configuration->has('Unoconv') ? $configuration->get('Unoconv') : null;

                $driver = new Driver\Unoconv($logger, $unoconv);

                return $driver->getDriver();
            });

        $this['exiftool.preview-extractor'] = $this->share(function() use ($configuration, $logger) {
                $driver = new Driver\ExiftoolExtractor($logger, null);

                return $driver->getDriver();
            });

        $this['mp4box'] = $this->share(function() use ($configuration, $logger) {
                $MP4Box = $configuration->has('MP4Box') ? $configuration->get('MP4Box') : null;

                $driver = new Driver\MP4Box($logger, $MP4Box);

                return $driver->getDriver();
            });

        $this['mediavorus'] = $this->share(function() use ($configuration, $logger) {

                $ffprobeConf = $configuration->has('ffprobe') ? $configuration->get('ffprobe') : null;

                $driver = new Driver\MediaVorus($logger, $ffprobeConf);

                return $driver->getDriver();
            });
    }

}
