<?php

namespace MediaAlchemyst;

use MediaVorus\MediaVorusServiceProvider;
use Silex\Application;

class MediaAlchemystServiceProviderTest extends \PHPUnit_Framework_TestCase
{

    public function getApplication()
    {
        return new Application();
    }

    public function testInit()
    {
        $app = $this->getApplication();
        $app->register(new MediaVorusServiceProvider());
        $app->register(new MediaAlchemystServiceProvider());

        $this->assertInstanceOf('\\MediaAlchemyst\\Alchemyst', $app['media-alchemyst']);
        $alchemyst = $app['media-alchemyst'];
        $this->assertEquals($alchemyst, $app['media-alchemyst']);
    }

    /**
     * @expectedException MediaAlchemyst\Exception\RuntimeException
     */
    public function testInitWithoutMediaVorus()
    {
        $app = $this->getApplication();
        $app->register(new MediaAlchemystServiceProvider());

        $app['media-alchemyst'];
    }
}
