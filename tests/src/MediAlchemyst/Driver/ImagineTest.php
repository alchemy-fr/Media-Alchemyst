<?php

namespace MediAlchemyst\Driver;

class ImagineTest extends \PHPUnit_Framework_TestCase
{

    protected function build($driver = null)
    {
        $logger = new \Monolog\Logger('test');
        $logger->pushHandler(new \Monolog\Handler\NullHandler());

        return new Imagine($logger, $driver);
    }

    /**
     * @covers MediAlchemyst\Driver\Imagine::__construct
     */
    public function testConstruct()
    {
        $this->build();
    }

    /**
     * @covers MediAlchemyst\Driver\Imagine::__construct
     */
    public function testSpecifyGD()
    {
        $driver = $this->build(Imagine::DRIVER_GD);

        $this->assertInstanceOf('\\Imagine\\GD\\Imagine', $driver->getDriver());
    }

    /**
     * @covers MediAlchemyst\Driver\Imagine::__construct
     * @expectedException MediAlchemyst\Exception\RuntimeException
     */
    public function testUnexistantDriver()
    {
        $this->build('42');
    }

    /**
     * @covers MediAlchemyst\Driver\Imagine::getDriver
     */
    public function testGetDriver()
    {
        $driver = $this->build();

        $this->assertInstanceOf('\\Imagine\\Image\\ImagineInterface', $driver->getDriver());
    }

}
