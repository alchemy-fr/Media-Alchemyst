<?php

namespace MediaAlchemyst\Driver;

class UnoconvTest extends \PHPUnit_Framework_TestCase
{

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

        $this->assertInstanceOf('\\SwfTools\\Binary\\Pdf2Swf', $driver->getDriver());
    }

}
