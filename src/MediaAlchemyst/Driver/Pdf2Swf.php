<?php

namespace MediaAlchemyst\Driver;

use Monolog\Logger;
use SwfTools\Binary\Pdf2Swf as Pdf2SwfBinary;
use SwfTools\Exception;

class Pdf2Swf extends Provider
{

    protected $driver;

    public function __construct(Logger $logger, $use_binary = null)
    {
        $this->logger = $logger;

        if ($use_binary)
        {
            $this->driver = new Pdf2SwfBinary($use_binary);
        }
        else
        {
            try
            {
                $this->driver = Pdf2SwfBinary::load(new \SwfTools\Configuration());
            }
            catch (Exception\BinaryNotFoundException $e)
            {
                throw new Exception\RuntimeException('No driver available');
            }
        }
    }

    public function getDriver()
    {
        return $this->driver;
    }

}
