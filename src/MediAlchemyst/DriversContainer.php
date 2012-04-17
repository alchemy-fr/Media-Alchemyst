<?php

namespace MediAlchemyst;

use \Symfony\Component\DependencyInjection\ParameterBag\ParameterBag;

class DriversContainer extends \Pimple
{

    public function __construct(ParameterBag $configuration, \Monolog\Logger $logger = null)
    {
        if ( ! $logger)
        {
            $logger = new \Monolog\Logger('Drivers');
            $logger->pushHandler(new \Monolog\Handler\NullHandler());
        }

        $this['FFMpeg'] = $this->share(function() use ($configuration, $logger)
          {
              $ffmpeg = $configuration->has('ffmpeg') ? $configuration->get('ffmpeg') : null;

              $driver = new \MediAlchemyst\Driver\FFMpeg($logger, $ffmpeg);

              return $driver->getDriver();
          });

        $this['Imagine'] = $this->share(function() use ($configuration, $logger)
          {
              $imagine = $configuration->has('imagine') ? $configuration->get('imagine') : null;

              $driver = new \MediAlchemyst\Driver\Imagine($logger, $imagine);

              return $driver->getDriver();
          });
    }

    public function getFFMpeg()
    {
        return $this['FFMpeg'];
    }

    public function getImagine()
    {
        return $this['Imagine'];
    }

}
