<?php

namespace MediaAlchemyst\Driver;

use Monolog\Logger;
use MP4Box\Exception as MP4BoxException;
use MP4Box\MP4Box as MP4BoxDriver;
use MediaAlchemyst\Exception;

class MP4Box extends Provider
{

    protected $driver;

    public function __construct(Logger $logger, $use_binary = null)
    {
        $this->logger = $logger;

        if ($use_binary) {
            $this->driver = new MP4BoxDriver($use_binary, $this->logger);
        } else {
            try {
                $this->driver = MP4BoxDriver::load($this->logger);
            } catch (MP4BoxException\BinaryNotFoundException $e) {
                throw new Exception\RuntimeException('No driver available');
            }
        }
    }

    public function getDriver()
    {
        return $this->driver;
    }

}
