<?php

namespace MediaAlchemyst\Driver;

use Monolog\Logger;
use SwfTools\Configuration;
use SwfTools\Processor\PDFFile;

class SwfToolsPDFFile extends AsbtractDriver
{
    protected $driver;

    public function __construct(Logger $logger, $pdf2swf_binary = null)
    {
        $this->logger = $logger;

        $conf = array();

        if ($pdf2swf_binary) {
            $conf['pdf2swf'] = $pdf2swf_binary;
        }

        $this->driver = new PDFFile(new Configuration($conf), $this->logger);
    }

    public function getDriver()
    {
        return $this->driver;
    }
}
