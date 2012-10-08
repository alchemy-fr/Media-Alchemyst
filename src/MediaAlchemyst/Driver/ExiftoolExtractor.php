<?php

namespace MediaAlchemyst\Driver;

use Monolog\Logger;
use PHPExiftool\Exiftool;
use PHPExiftool\PreviewExtractor;

class ExiftoolExtractor extends AbstractDriver
{

    protected $driver;

    public function __construct(Exiftool $exiftool, Logger $logger, $use_binary = null)
    {
        $this->logger = $logger;

        $this->driver = new PreviewExtractor($exiftool);
    }

    public function getDriver()
    {
        return $this->driver;
    }

}
