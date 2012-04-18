<?php

namespace MediAlchemyst\Driver;

require_once dirname(__FILE__) . '/../../../../src/MediAlchemyst/Driver/Unoconv.php';

class UnoconvTest extends \PHPUnit_Framework_TestCase
{

    protected function build($binary = null)
    {
        $logger = new \Monolog\Logger('test');
        $logger->pushHandler(new \Monolog\Handler\NullHandler());

        return new Unoconv($logger, $binary);
    }

    /**
     * @covers MediAlchemyst\Driver\Unoconv::__construct
     */
    public function testConstruct()
    {
        $this->build();
    }

    /**
     * @covers MediAlchemyst\Driver\Unoconv::__construct
     */
    public function testConstructOwnBinary()
    {
        $this->build('Albator');
    }

    /**
     * @covers MediAlchemyst\Driver\Unoconv::getDriver
     */
    public function testGetDriver()
    {
        $driver = $this->build();

        $this->assertInstanceOf('\\SwfTools\\Binary\\Pdf2Swf', $driver->getDriver());
    }

}
