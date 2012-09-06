<?php

namespace MediaAlchemyst\Driver;

use Monolog\Logger;
use Monolog\Handler\NullHandler;
use Symfony\Component\Process\ExecutableFinder;

class UnoconvTest extends \PHPUnit_Framework_TestCase
{

    public function setUp()
    {
        $executableFinder = new ExecutableFinder();
        if ( ! $executableFinder->find('unoconv')) {
            $this->markTestSkipped('Unoconv is not installed');
        }
    }

    protected function build($binary = null)
    {
        $logger = new Logger('test');
        $logger->pushHandler(new NullHandler());

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
     * @expectedException MediaAlchemyst\Exception\RuntimeException
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
