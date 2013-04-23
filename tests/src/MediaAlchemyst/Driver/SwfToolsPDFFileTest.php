<?php

namespace MediaAlchemyst\Driver;

use Symfony\Component\Process\ExecutableFinder;
use Monolog\Logger;
use Monolog\Handler\NullHandler;

class SwfToolsPDFFileTest extends \PHPUnit_Framework_TestCase
{
    public function testConstructWithTimeoutOption()
    {
        $logger = new Logger('tests');
        $logger->pushHandler(new NullHandler());

        $driver = new SwfToolsPDFFile($logger, null, 40);
        $this->assertInstanceOf('SwfTools\Processor\PDFFile', $driver->getDriver());
    }

    public function testConstructWithAllOptions()
    {
        $logger = new Logger('tests');
        $logger->pushHandler(new NullHandler());

        $finder = new ExecutableFinder();
        $pdf2swf = $finder->find('pdf2swf');

        if (!$pdf2swf) {
            $this->markTestSkipped('Unable to detect pdf2swf that is required for this test');
        }

        $driver = new SwfToolsPDFFile($logger, $pdf2swf, 40);
        $this->assertInstanceOf('SwfTools\Processor\PDFFile', $driver->getDriver());
    }
}
