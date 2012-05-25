<?php

namespace MediaAlchemyst\Driver;

class UnoconvTest extends \PHPUnit_Framework_TestCase
{

    public function setUp()
    {
        $executableFinder = new \Symfony\Component\Process\ExecutableFinder();
        if ( ! $executableFinder->find('unoconv')) {
            $this->markTestSkipped('Unoconv is not installed');
        }
    }

    protected function build($binary = null)
    {
        $logger = new \Monolog\Logger('test');
        $logger->pushHandler(new \Monolog\Handler\NullHandler());

        return new Unoconv($logger, $binary);
    }

    /**
     * @covers MediaAlchemyst\Driver\Unoconv::__construct
     */
    public function testConstruct()
    {
        $this->build();
    }

    /**
     * @covers MediaAlchemyst\Driver\Unoconv::__construct
     */
    public function testConstructOwnBinary()
    {
        $this->build('Albator');
    }

    /**
     * @covers MediaAlchemyst\Driver\Unoconv::getDriver
     */
    public function testGetDriver()
    {
        $driver = $this->build();

        $this->assertInstanceOf('\\Unoconv\\Unoconv', $driver->getDriver());
    }
}
