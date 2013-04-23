<?php

namespace MediaAlchemyst;

use MediaAlchemyst\Exception\RuntimeException;
use Silex\Application;
use Silex\ServiceProviderInterface;

class MediaAlchemystServiceProvider implements ServiceProviderInterface
{

    public function register(Application $app)
    {
        $app['media-alchemyst'] = $app->share(function(Application $app) {

            $drivers = new DriversContainer();

            $drivers['mediavorus'] = function() use ($app) {
                return $app['mediavorus'];
            };

            // Try to use official Monolog Service Provider
            if (isset($app['monolog'])) {
                $drivers['logger'] = function() use ($app) {
                    return $app['monolog'];
                };
            }

            // As exiftool is a dependency of mediavorus, use it
            $drivers['exiftool.exiftool'] = function() use ($app) {
                return $app['exiftool.processor'];
            };

            // If some parameters defined with the media-alchemyst prefix
            // inject them in DriversContainer
            foreach ($app->keys() as $key) {
                if (strpos($key, 'media-alchemyst.') === 0) {
                    $drivers[substr($key, 16)] = function() use ($app, $key) {
                        return $app[$key];
                    };
                }
            }

            return new Alchemyst($drivers);
        });
    }

    public function boot(Application $app)
    {
        if (!isset($app['mediavorus'])) {
            throw new RuntimeException('Media-Alchemyst requires MediaVorus Service Provider');
        }
    }
}
