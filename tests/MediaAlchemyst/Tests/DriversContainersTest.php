<?php

namespace MediaAlchemyst\Tests;

use MediaAlchemyst\DriversContainer;

class DriversContainersTest extends AbstractDriversContainerTest
{
    public function getDrivers()
    {
        return new DriversContainer();
    }
}
