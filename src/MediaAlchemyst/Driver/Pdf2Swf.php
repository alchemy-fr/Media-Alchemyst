<?php

namespace MediaAlchemyst\Driver;

use Monolog\Logger;
use SwfTools\Configuration;
use SwfTools\Binary\Pdf2swf as Pdf2swfBinary;
use SwfTools\Exception\BinaryNotFoundException;
use MediaAlchemyst\Exception\RuntimeException;

class Pdf2Swf extends Provider
{
    protected $driver;

    public function __construct(Logger $logger, $use_binary = null)
    {
        $this->logger = $logger;

        try {
            if ($use_binary) {
                $this->driver = new Pdf2swfBinary($use_binary, $this->logger);
            } else {
                $this->driver = Pdf2swfBinary::load(new Configuration(), $this->logger);
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
