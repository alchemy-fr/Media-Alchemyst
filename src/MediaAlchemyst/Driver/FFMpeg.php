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

    public function __construct(Logger $logger, $useBinary = null, $useProberBinary = null)
    {
        $this->logger = $logger;

        if ($useBinary) {
            $this->driver = new FFMpegDriver($useBinary, $this->logger);
        } else {
            try {
                $this->driver = FFMpegDriver::load($this->logger);

                if ($useProberBinary) {
                    $prober = new FFMpegProber($useProberBinary, $this->logger);
                } else {
                    $prober = FFMpegProber::load($this->logger);
                }

                $this->driver->setProber($prober);
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
