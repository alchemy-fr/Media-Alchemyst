<?php

namespace MediaAlchemyst\Driver;

class Pdf2SwfTest extends \PHPUnit_Framework_TestCase
{

    public function setUp()
    {
        $executableFinder = new \Symfony\Component\Process\ExecutableFinder();
        if ( ! $executableFinder->find('pdf2swf')) {
            $this->markTestSkipped('Pdf2Swf is not installed');
        }
    }

    protected function build($binary = null)
    {
        $logger = new \Monolog\Logger('test');
        $logger->pushHandler(new \Monolog\Handler\NullHandler());

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
