<?php

namespace MediaAlchemyst;

use Monolog\Logger;
use Monolog\Handler\NullHandler;
use MediaAlchemyst\Driver\ExiftoolExtractor;
use MediaAlchemyst\Driver\FFMpeg;
use MediaAlchemyst\Driver\Imagine;
use MediaAlchemyst\Driver\MP4Box;
use MediaAlchemyst\Driver\MediaVorus;
use MediaAlchemyst\Driver\SwfToolsFlashFile;
use MediaAlchemyst\Driver\SwfToolsPDFFile;
use MediaAlchemyst\Driver\Unoconv;
use Pimple;
use PHPExiftool\Exiftool;

class DriversContainer extends Pimple
{

    public function __construct()
    {
        $this['logger.name'] = 'Media-Alchemyst drivers logger';

        $this['logger.level'] = function (Pimple $container) {
            return Logger::DEBUG;
        };

        $this['logger.handler'] = $this->share(function(Pimple $container) {
            return new NullHandler($container['logger.level']);
        });

        $bridge = class_exists('Symfony\Bridge\Monolog\Logger');

        $this['logger.class'] = $bridge ? 'Symfony\Bridge\Monolog\Logger' : 'Monolog\Logger';

        $this['logger'] = $this->share(function(Pimple $container) {
            $logger = new $container['logger.class']($container['logger.name']);
            $logger->pushHandler($container['logger.handler']);

            return $logger;
        });

        $this['ffmpeg.ffprobe.binary'] = $this['mp4box.binary'] = $this['unoconv.binary']
            = $this['pdf2swf.binary'] = $this['swf-render.binary'] = $this['swf-extract.binary']
            = $this['imagine.driver'] = $this['ffmpeg.ffmpeg.binary'] = null;

        $this['ffmpeg.threads'] = 1;

        $this['ffmpeg.ffmpeg'] = $this->share(function(Pimple $container) {
            $driver = new FFMpeg($container['logger'], $container['ffmpeg.ffmpeg.binary'], $container['ffmpeg.ffprobe.binary'], $container['ffmpeg.threads']);

            return $driver->getDriver();
        });

        $this['imagine'] = $this->share(function(Pimple $container) {
            $driver = new Imagine($container['logger'], $container['imagine.driver']);

            return $driver->getDriver();
        });

        $this['swftools.flash-file'] = $this->share(function(Pimple $container) {
            $driver = new SwfToolsFlashFile($container['logger'], $container['swf-extract.binary'], $container['swf-render.binary']);

            return $driver->getDriver();
        });

        $this['swftools.pdf-file'] = $this->share(function(Pimple $container) {
            $driver = new SwfToolsPDFFile($container['logger'], $container['pdf2swf.binary']);

            return $driver->getDriver();
        });

        $this['unoconv'] = $this->share(function(Pimple $container) {
            $driver = new Unoconv($container['logger'], $container['unoconv.binary']);

            return $driver->getDriver();
        });

        $this['exiftool.exiftool'] = $this->share(function() {
            return new Exiftool();
        });

        $this['exiftool.preview-extractor'] = $this->share(function(Pimple $container) {
            $driver = new ExiftoolExtractor($container['exiftool.exiftool'], $container['logger'], null);

            return $driver->getDriver();
        });

        $this['mp4box'] = $this->share(function(Pimple $container) {
            $driver = new MP4Box($container['logger'], $container['mp4box.binary']);

            return $driver->getDriver();
        });

        $this['mediavorus'] = $this->share(function(Pimple $container) {
            $driver = new MediaVorus($container['logger'], $container['ffmpeg.ffprobe.binary']);

            return $driver->getDriver();
        });
    }

    public static function create()
    {
        return new static();
    }
}
