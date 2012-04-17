<?php

namespace MediAlchemyst\Driver;

class FFMpegTest extends \PHPUnit_Framework_TestCase
{

    protected function build($binary = null)
    {
        $logger = new \Monolog\Logger('test');
        $logger->pushHandler(new \Monolog\Handler\NullHandler());

        return new FFMpeg($logger, $binary);
    }

    /**
     * @covers MediAlchemyst\Driver\FFMpeg::__construct
     */
    public function testConstruct()
    {
        $this->build();
    }

    /**
     * @covers MediAlchemyst\Driver\FFMpeg::__construct
     */
    public function testConstructOwnBinary()
    {
        $this->build('Albator');
    }

    /**
     * @covers MediAlchemyst\Driver\FFMpeg::getDriver
     */
    public function testGetDriver()
    {
        $driver = $this->build();

        $this->assertInstanceOf('\\FFMpeg\\FFMpeg', $driver->getDriver());
    }

}
