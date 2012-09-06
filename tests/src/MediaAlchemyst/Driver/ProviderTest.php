<?php

namespace MediaAlchemyst\Driver;

use Monolog\Logger;

class ProviderTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @covers MediaAlchemyst\Driver\AbstractDriver::setLogger
     */
    public function testSetLogger()
    {
        $logger = new Logger('test');

        $provider = new TestProvider();
        $provider->setLogger($logger);

        $this->assertEquals($logger, $provider->getLogger());
    }

}

class TestProvider extends AbstractDriver
{

    public function getLogger()
    {
        return $this->logger;
    }

    public function getDriver()
    {

    }

}
