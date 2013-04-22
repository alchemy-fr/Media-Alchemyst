<?php

namespace MediaAlchemyst\Driver;

use FFMpeg\Exception\BinaryNotFoundException;
use FFMpeg\FFMpeg as FFMpegDriver;
use FFMpeg\FFProbe;
use MediaAlchemyst\Exception\RuntimeException;
use Monolog\Logger;

class FFMpeg extends AbstractDriver
{
    protected $driver;

    public function __construct(Logger $logger, $useBinary = null, $useProberBinary = null, $threads = 1, $timeout = 60)
    {
        $this->logger = $logger;

        try {
            if ($useBinary) {
                $this->driver = new FFMpegDriver($useBinary, $this->logger, $timeout);
            } else {
                $this->driver = FFMpegDriver::load($this->logger, $timeout);
            }
        } catch (BinaryNotFoundException $e) {
            throw new RuntimeException('No driver available');
        }

        try {

            if ($useProberBinary) {
                $prober = new FFProbe($useProberBinary, $this->logger, $timeout);
            } else {
                $prober = FFProbe::load($this->logger, $timeout);
            }
        } catch (BinaryNotFoundException $e) {
            throw new RuntimeException('No driver available');
        }

        $this->driver->setProber($prober);
        $this->driver->setThreads($threads);
    }

    public function getDriver()
    {
        return $this->driver;
    }
}
