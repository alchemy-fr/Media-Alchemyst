<?php

namespace MediaAlchemyst\Tests;

use FFMpeg\FFMpegServiceProvider;
use Neutron\Silex\Provider\ImagineServiceProvider;
use MediaVorus\MediaVorusServiceProvider;
use MP4Box\MP4BoxServiceProvider;
use PHPExiftool\PHPExiftoolServiceProvider;
use Silex\Application;
use SwfTools\SwfToolsServiceProvider;
use Unoconv\UnoconvServiceProvider;
use MediaAlchemyst\MediaAlchemystServiceProvider;

class ApplicationServiceProviderTest extends AbstractDriversContainerTest
{
    public function getDrivers()
    {
        $app = new Application();
        $app->register(new MediaAlchemystServiceProvider());
        $app->register(new PHPExiftoolServiceProvider());
        $app->register(new MP4BoxServiceProvider());
        $app->register(new UnoconvServiceProvider());
        $app->register(new MediaVorusServiceProvider());
        $app->register(new ImagineServiceProvider());
        $app->register(new FFMpegServiceProvider());
        $app->register(new SwfToolsServiceProvider());

        return $app;
    }
}
