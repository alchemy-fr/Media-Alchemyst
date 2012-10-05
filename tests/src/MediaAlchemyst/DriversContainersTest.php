<?php

namespace MediaAlchemyst;

use Silex\Application;
use Silex\ServiceProviderInterface;
use Symfony\Component\Process\ExecutableFinder;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBag;

require_once __DIR__ . '/AbstractDriversContainerTest.php';

class DriversContainersTest extends AbstractDriversContainerTest
{
    public function getDrivers()
    {
        return new DriversContainer(new ParameterBag());
    }
}
