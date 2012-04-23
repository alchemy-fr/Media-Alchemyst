<?php

namespace MediaAlchemyst\Driver;

class MP4BoxTest extends \PHPUnit_Framework_TestCase
{

    protected function build($binary = null)
    {
        $logger = new \Monolog\Logger('test');
        $logger->pushHandler(new \Monolog\Handler\NullHandler());

        return new MP4Box($logger, $binary);
    }

    /**
     * @covers MediaAlchemyst\Driver\MP4Box::__construct
     */
    public function testConstruct()
    {
        $this->build();
    }

    /**
     * @covers MediaAlchemyst\Driver\MP4Box::__construct
     */
    public function testConstructOwnBinary()
    {
        $this->build('Albator');
    }

    /**
     * @covers MediaAlchemyst\Driver\MP4Box::getDriver
     */
    public function testGetDriver()
    {
        $driver = $this->build();

        $this->assertInstanceOf('\\MP4Box\\MP4Box', $driver->getDriver());
    }

}
