<?php

namespace MediAlchemyst\Driver;

require_once dirname(__FILE__) . '/../../../../src/MediAlchemyst/Driver/SwfRender.php';

class SwfRenderTest extends \PHPUnit_Framework_TestCase
{

    protected function build($binary = null)
    {
        $logger = new \Monolog\Logger('test');
        $logger->pushHandler(new \Monolog\Handler\NullHandler());

        return new SwfRender($logger, $binary);
    }

    /**
     * @covers MediAlchemyst\Driver\SwfRender::__construct
     */
    public function testConstruct()
    {
        $this->build();
    }

    /**
     * @covers MediAlchemyst\Driver\SwfRender::__construct
     */
    public function testConstructOwnBinary()
    {
        $this->build('Albator');
    }

    /**
     * @covers MediAlchemyst\Driver\SwfRender::getDriver
     */
    public function testGetDriver()
    {
        $driver = $this->build();

        $this->assertInstanceOf('\\SwfTools\\Binary\\SwfRender', $driver->getDriver());
    }

}
