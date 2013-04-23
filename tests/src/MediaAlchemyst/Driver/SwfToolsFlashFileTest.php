<?php

namespace MediaAlchemyst\Driver;

use Symfony\Component\Process\ExecutableFinder;
use Monolog\Logger;
use Monolog\Handler\NullHandler;

class SwfToolsFlashFileTest extends \PHPUnit_Framework_TestCase
{
    public function testConstructWithTimeoutOption()
    {
        $logger = new Logger('tests');
        $logger->pushHandler(new NullHandler());

        $driver = new SwfToolsFlashFile($logger, null, null, 40);
        $this->assertInstanceOf('SwfTools\Processor\FlashFile', $driver->getDriver());
    }

    public function testConstructWithAllOptions()
    {
        $logger = new Logger('tests');
        $logger->pushHandler(new NullHandler());

        $finder = new ExecutableFinder();

        $swfextract = $finder->find('swfextract');
        $swfrender = $finder->find('swfrender');

        if (!$swfextract || !$swfrender) {
            $this->markTestSkipped('Unable to detect pdf2swf that is required for this test');
        }

        $driver = new SwfToolsFlashFile($logger, $swfextract, $swfrender, 40);
        $this->assertInstanceOf('SwfTools\Processor\FlashFile', $driver->getDriver());
    }
}
