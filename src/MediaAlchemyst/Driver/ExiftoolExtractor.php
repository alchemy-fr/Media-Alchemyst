<?php

namespace MediaAlchemyst\Driver;

use Monolog\Logger;
use FFMpeg\Exception as FFMpegException;
use FFMpeg\FFMpeg as FFMpegDriver;

class ExiftoolExtractor extends Provider
{

    protected $driver;

    public function __construct(Logger $logger, $use_binary = null)
    {
        $this->logger = $logger;

        $this->driver = new \PHPExiftool\PreviewExtractor();
    }

    public function getDriver()
    {
        return $this->driver;
    }

}
