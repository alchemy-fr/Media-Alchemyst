<?php

namespace MediaAlchemyst\Driver;

use Monolog\Logger;
use SwfTools\Configuration;
use SwfTools\Processor\PDFFile;

class SwfToolsPDFFile extends AbstractDriver
{
    protected $driver;

    public function __construct(Logger $logger, $pdf2swf_binary = null, $timeout = null)
    {
        $this->logger = $logger;

        $conf = array();

        if (null !== $pdf2swf_binary) {
            $conf['pdf2swf'] = $pdf2swf_binary;
        }
        if (null !== $timeout) {
            $conf['timeout'] = $timeout;
        }

        $this->driver = new PDFFile(new Configuration($conf), $this->logger);
    }

    public function getDriver()
    {
        return $this->driver;
    }
}
