<?php

namespace MediaAlchemyst;

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
        return new Exiftool();
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
        $logger = $this->getMockBuilder('\\Monolog\\Logger')
            ->disableOriginalConstructor()
            ->getMock();
        return FFProbe::load($logger);
    }
}
