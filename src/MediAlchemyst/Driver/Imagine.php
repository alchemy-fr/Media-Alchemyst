<?php

namespace MediAlchemyst\Driver;

class Imagine extends Provider
{

    protected $driver;

    public function __construct(\Monolog\Logger $logger, $workWithDriver = null)
    {
        $this->logger = $logger;

        $drivers = array(
          self::DRIVER_GMAGICK => '\\Imagine\\Gmagick\\Imagine',
          self::DRIVER_IMAGICK => '\\Imagine\\Imagick\\Imagine',
          self::DRIVER_GD      => '\\Imagine\\GD\\Imagine',
        );

        foreach ($drivers as $driver)
        {
            if ($driver !== $workWithDriver)
            {
                continue;
            }

            try
            {
                $this->driver = new $driver;
            }
            catch (\Imagine\Exception\RuntimeException $e)
            {
                $this->logger->addWarning($e->getMessage());
                continue;
            }
        }

        if ( ! $this->driver)
        {
            throw new \MediAlchemyst\Exception\RuntimeException('No driver available');
        }
    }

    public function getDriver()
    {
        return $this->driver;
    }

}
