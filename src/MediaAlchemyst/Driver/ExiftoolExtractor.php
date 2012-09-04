<?php

namespace MediaAlchemyst\Driver;

use Monolog\Logger;
use PHPExiftool\Exiftool;
use PHPExiftool\PreviewExtractor;

class ExiftoolExtractor extends AsbtractDriver
{

    protected $driver;

    public function __construct(Logger $logger, $use_binary = null)
    {
        $this->logger = $logger;

        $this->driver = new PreviewExtractor(new Exiftool());
    }

    public function getDriver()
    {
        return $this->driver;
    }

}
