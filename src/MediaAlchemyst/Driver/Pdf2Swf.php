<?php

namespace MediaAlchemyst\Driver;

use Monolog\Logger;
use SwfTools\Binary\Pdf2swf as Pdf2swfBinary;
use SwfTools\Exception as SwfToolsException;
use MediaAlchemyst\Exception;

class Pdf2Swf extends Provider
{

    protected $driver;

    public function __construct(Logger $logger, $use_binary = null)
    {
        $this->logger = $logger;

        if ($use_binary)
        {
            $this->driver = new Pdf2swfBinary($use_binary);
        }
        else
        {
            try
            {
                $this->driver = Pdf2swfBinary::load(new \SwfTools\Configuration());
            }
            catch (SwfToolsException\BinaryNotFoundException $e)
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
