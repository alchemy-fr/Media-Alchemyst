<?php

namespace MediAlchemyst\Driver;

class FFMpeg extends Provider
{

    protected $driver;

    public function __construct(\Monolog\Logger $logger, $use_binary = null)
    {
        $this->logger = $logger;

        if ($use_binary)
        {
            $this->driver = new \FFMpeg\FFMpeg($use_binary, $this->logger);
        }
        else
        {
            $this->driver = \FFMpeg\FFMpeg::load($this->logger);
        }
    }

    public function getDriver()
    {
        return $this->driver;
    }

}
