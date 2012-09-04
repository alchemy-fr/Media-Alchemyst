<?php

namespace MediaAlchemyst\Driver;

use MediaAlchemyst\Exception\RuntimeException;
use Monolog\Logger;
use MP4Box\Exception\BinaryNotFoundException;
use MP4Box\MP4Box as MP4BoxDriver;

class MP4Box extends Provider
{
    protected $driver;

    public function __construct(Logger $logger, $use_binary = null)
    {
        $this->logger = $logger;

        try {
            if ($use_binary) {
                $this->driver = new MP4BoxDriver($use_binary, $this->logger);
            } else {
                $this->driver = MP4BoxDriver::load($this->logger);
            }
        } catch (BinaryNotFoundException $e) {
            throw new RuntimeException('No driver available');
        }
    }

    public function getDriver()
    {
        return $this->driver;
    }
}
