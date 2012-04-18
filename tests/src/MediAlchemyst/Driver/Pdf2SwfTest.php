<?php

namespace MediAlchemyst\Driver;

require_once dirname(__FILE__) . '/../../../../src/MediAlchemyst/Driver/Pdf2Swf.php';


namespace MediAlchemyst\Driver;

class Pdf2SwfTest extends \PHPUnit_Framework_TestCase
{

    protected function build($binary = null)
    {
        $logger = new \Monolog\Logger('test');
        $logger->pushHandler(new \Monolog\Handler\NullHandler());

        return new Pdf2Swf($logger, $binary);
    }

    /**
     * @covers MediAlchemyst\Driver\Pdf2Swf::__construct
     */
    public function testConstruct()
    {
        $this->build();
    }

    /**
     * @covers MediAlchemyst\Driver\Pdf2Swf::__construct
     */
    public function testConstructOwnBinary()
    {
        $this->build('Albator');
    }

    /**
     * @covers MediAlchemyst\Driver\Pdf2Swf::getDriver
     */
    public function testGetDriver()
    {
        $driver = $this->build();

        $this->assertInstanceOf('\\SwfTools\\Binary\\Pdf2Swf', $driver->getDriver());
    }

}
