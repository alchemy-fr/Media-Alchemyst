<?php

namespace MediaAlchemyst\Driver;

use Monolog\Logger;
use FFMpeg\Exception as FFMpegException;
use FFMpeg\FFMpeg as FFMpegDriver;
use MediaAlchemyst\Exception;

class FFMpeg extends Provider
{

    protected $driver;

    public function __construct(Logger $logger, $use_binary = null)
    {
        $this->logger = $logger;

        if ($use_binary) {
            $this->driver = new FFMpegDriver($use_binary, $this->logger);
        } else {
            try {
                $this->driver = FFMpegDriver::load($this->logger);
            } catch (FFMpegException\BinaryNotFoundException $e) {
                throw new Exception\RuntimeException('No driver available');
            }
        }
    }

    public function getDriver()
    {
        return $this->driver;
    }

}
