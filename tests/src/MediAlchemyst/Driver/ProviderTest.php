<?php

namespace MediAlchemyst\Driver;

class ProviderTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @covers MediAlchemyst\Driver\Provider::setLogger
     */
    public function testSetLogger()
    {
        $logger = new \Monolog\Logger('test');

        $provider = new TestProvider();
        $provider->setLogger($logger);

        $this->assertEquals($logger, $provider->getLogger());
    }

}

class TestProvider extends Provider
{

    public function getLogger()
    {
        return $this->logger;
    }

    public function getDriver()
    {

    }

}
