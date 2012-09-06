<?php

namespace MediaAlchemyst\Driver;

use Symfony\Component\Process\ExecutableFinder;
use Monolog\Logger;
use Monolog\Handler\NullHandler;

class Pdf2SwfTest extends \PHPUnit_Framework_TestCase
{

    public function setUp()
    {
        $executableFinder = new ExecutableFinder();
        if ( ! $executableFinder->find('pdf2swf')) {
            $this->markTestSkipped('Pdf2Swf is not installed');
        }
    }

    protected function build($binary = null)
    {
        $logger = new Logger('test');
        $logger->pushHandler(new NullHandler());

        return new Pdf2Swf($logger, $binary);
    }

    /**
     * @covers MediaAlchemyst\Driver\Pdf2Swf::__construct
     */
    public function testConstruct()
    {
        $this->build();
    }

    /**
     * @covers MediaAlchemyst\Driver\Pdf2Swf::__construct
     * @expectedException MediaAlchemyst\Exception\RuntimeException
     */
    public function testConstructOwnBinary()
    {
        $this->build('Albator');
    }

    /**
     * @covers MediaAlchemyst\Driver\Pdf2Swf::getDriver
     */
    public function testGetDriver()
    {
        $driver = $this->build();

        $this->assertInstanceOf('\\SwfTools\\Binary\\Pdf2Swf', $driver->getDriver());
    }

}
