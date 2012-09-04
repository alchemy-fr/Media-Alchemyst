<?php

namespace MediaAlchemyst\Driver;

use Monolog\Logger;
use FFMpeg\Exception as FFMpegException;
use FFMpeg\FFMpeg as FFMpegDriver;
use FFMpeg\FFProbe as FFMpegProber;
use MediaAlchemyst\Exception;

class FFMpeg extends Provider
{
    protected $driver;

    public function __construct(Logger $logger, $useBinary = null, $useProberBinary = null, $threads = 1)
    {
        $this->logger = $logger;

        try {
            if ($useBinary) {
                $this->driver = new FFMpegDriver($useBinary, $this->logger);
            } else {
                $this->driver = FFMpegDriver::load($this->logger);
            }
        } catch (FFMpegException\BinaryNotFoundException $e) {
            throw new Exception\RuntimeException('No driver available');
        }

        try {

            if ($useProberBinary) {
                $prober = new FFMpegProber($useProberBinary, $this->logger);
            } else {
                $prober = FFMpegProber::load($this->logger);
            }
        } catch (FFMpegException\BinaryNotFoundException $e) {
            throw new Exception\RuntimeException('No driver available');
        }

        $this->driver->setProber($prober);
        $this->driver->setThreads($threads);
    }

    public function getDriver()
    {
        return $this->driver;
    }
}
