<?php

namespace MediaAlchemyst;

use Silex\Application;
use Silex\ServiceProviderInterface;

class MediaAlchemystServiceProvider implements ServiceProviderInterface
{
    public function register(Application $app)
    {
        $app['media-alchemyst.configuration'] = array();
        $app['media-alchemyst.logger'] = null;

        $app['media-alchemyst.drivers'] = $app->share(function (Application $app){
            $drivers = DriversContainer::create();
            $drivers['configuration'] = $app['media-alchemyst.configuration'];

            if (null !== $app['media-alchemyst.logger']) {
                $drivers['logger'] = $app['media-alchemyst.logger'];
            }

            return $drivers;
        });

        $app['media-alchemyst'] = $app->share(function(Application $app) {
            return new Alchemyst($app['media-alchemyst.drivers']);
        });
    }

    public function boot(Application $app)
    {
    }
}
