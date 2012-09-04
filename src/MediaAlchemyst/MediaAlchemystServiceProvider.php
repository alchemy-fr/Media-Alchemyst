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

            if ( ! isset($app['mediavorus'])) {
                throw new RuntimeException('Media-Alchemyst requires MediaVorus Service Provider');
            }

            return new Alchemyst($app);
        });
    }

    public function boot(Application $app)
    {
    }
}
