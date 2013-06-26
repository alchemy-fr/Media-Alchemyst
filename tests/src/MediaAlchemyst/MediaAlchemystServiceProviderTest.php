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
        $app->register(new MediaAlchemystServiceProvider(), array(
            'media-alchemyst.configuration' => array(
                'ffmpeg.ffmpeg.timeout' => 124,
            )
        ));

        $this->assertInstanceOf('\\MediaAlchemyst\\Alchemyst', $app['media-alchemyst']);
        $alchemyst = $app['media-alchemyst'];
        $this->assertEquals($alchemyst, $app['media-alchemyst']);

        $drivers = $app['media-alchemyst']->getDrivers();

        $this->assertEquals(124, $drivers['ffmpeg.ffmpeg']->getFFMpegDriver()->getProcessBuilderFactory()->getTimeout());
    }
}
