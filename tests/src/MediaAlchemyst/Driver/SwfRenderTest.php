<?php

namespace MediaAlchemyst\Driver;

class SwfRenderTest extends \PHPUnit_Framework_TestCase
{

    public function setUp()
    {
        $executableFinder = new \Symfony\Component\Process\ExecutableFinder();
        if ( ! $executableFinder->find('swfrender')) {
            $this->markTestSkipped('SwfRender is not installed');
        }
    }

    protected function build($binary = null)
    {
        $logger = new \Monolog\Logger('test');
        $logger->pushHandler(new \Monolog\Handler\NullHandler());

        return new SwfRender($logger, $binary);
    }

    /**
     * @covers MediaAlchemyst\Driver\SwfRender::__construct
     */
    public function testConstruct()
    {
        $this->build();
    }

    /**
     * @covers MediaAlchemyst\Driver\SwfRender::__construct
     */
    public function testConstructOwnBinary()
    {
        $this->build('Albator');
    }

    /**
     * @covers MediaAlchemyst\Driver\SwfRender::getDriver
     */
    public function testGetDriver()
    {
        $driver = $this->build();

        $this->assertInstanceOf('\\SwfTools\\Binary\\SwfRender', $driver->getDriver());
    }

}
