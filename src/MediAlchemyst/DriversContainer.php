<?php

namespace MediAlchemyst;

use \Symfony\Component\DependencyInjection\ParameterBag\ParameterBag;

class DriversContainer extends \Pimple
{

    public function __construct(ParameterBag $configuration, \Monolog\Logger $logger = null)
    {
        if ( ! $logger)
        {
            $logger = new \Monolog\Logger();
            $logger->pushHandler(new \Monolog\Handler\NullHandler());
        }

        $this['FFMpeg'] = $this->share(function() use ($logger)
          {
              $driver = new \MediAlchemyst\Driver\FFMpeg($logger);

              return $driver->getDriver();
          });

        $this['Imagine'] = $this->share(function() use ($logger)
          {
              $driver = new \MediAlchemyst\Driver\Imagine($logger);

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
