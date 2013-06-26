<?php

namespace MediaAlchemyst\Tests;

use Monolog\Logger;
use Monolog\Handler\NullHandler;
use MediaVorus\MediaVorus;
use PHPExiftool\Reader;
use PHPExiftool\RDFParser;
use PHPExiftool\Writer;
use PHPExiftool\Exiftool;
use FFMpeg\FFProbe;

class AbstractAlchemystTester extends \PHPUnit_Framework_TestCase
{
    public function getMediaVorus()
    {
        return new MediaVorus($this->getReader(), $this->getWriter(), $this->getProbe());
    }

    public function getExiftool()
    {
        $logger = new Logger('test');
        $logger->pushHandler(new NullHandler());

        return new Exiftool($logger);
    }

    public function getReader()
    {
        return new Reader($this->getExiftool(), new RDFParser());
    }

    public function getWriter()
    {
        return new Writer($this->getExiftool());
    }

    public function getProbe()
    {
        return FFProbe::create();
    }
}
