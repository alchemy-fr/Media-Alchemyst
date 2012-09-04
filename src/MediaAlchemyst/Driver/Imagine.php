<?php

namespace MediaAlchemyst\Driver;

use Monolog\Logger;
use Imagine\Exception\RuntimeException as ImagineRuntimeException;
use MediaAlchemyst\Exception\RuntimeException;

class Imagine extends Provider
{

    const DRIVER_GMAGICK = 'Gmagick';
    const DRIVER_IMAGICK = 'Imagick';
    const DRIVER_GD      = 'GD';

    protected $driver;

    public function __construct(Logger $logger, $workWithDriver = null)
    {
        $this->logger = $logger;

        $drivers = array(
          self::DRIVER_GMAGICK => '\\Imagine\\Gmagick\\Imagine',
          self::DRIVER_IMAGICK => '\\Imagine\\Imagick\\Imagine',
          self::DRIVER_GD      => '\\Imagine\\Gd\\Imagine',
        );

        foreach ($drivers as $driverName => $driver) {
            if ($workWithDriver && $driverName !== $workWithDriver) {
                continue;
            }

            try {
                $this->driver = new $driver;
                break;
            } catch (ImagineRuntimeException $e) {
                $this->logger->addWarning($e->getMessage());
                continue;
            }
        }

        if ( ! $this->driver) {
            throw new RuntimeException('No driver available');
        }
    }

    public function getDriver()
    {
        return $this->driver;
    }

}
