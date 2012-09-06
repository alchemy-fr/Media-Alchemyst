<?php

namespace MediaAlchemyst\Driver;

use Monolog\Logger;
use Monolog\Handler\NullHandler;

class ImagineTest extends \PHPUnit_Framework_TestCase
{

    protected function build($driver = null)
    {
        $logger = new Logger('test');
        $logger->pushHandler(new NullHandler());

        return new Imagine($logger, $driver);
    }

    /**
     * @covers MediaAlchemyst\Driver\Imagine::__construct
     */
    public function testConstruct()
    {
        $this->build();
    }

    /**
     * @covers MediaAlchemyst\Driver\Imagine::__construct
     */
    public function testSpecifyGD()
    {
        $driver = $this->build(Imagine::DRIVER_GD);

        $this->assertInstanceOf('\\Imagine\\GD\\Imagine', $driver->getDriver());
    }

    /**
     * @covers MediaAlchemyst\Driver\Imagine::__construct
     * @covers MediaAlchemyst\Exception\RuntimeException
     * @expectedException MediaAlchemyst\Exception\RuntimeException
     */
    public function testUnexistantDriver()
    {
        $this->build('42');
    }

    /**
     * @covers MediaAlchemyst\Driver\Imagine::getDriver
     */
    public function testGetDriver()
    {
        $driver = $this->build();

        $this->assertInstanceOf('\\Imagine\\Image\\ImagineInterface', $driver->getDriver());
    }

}
