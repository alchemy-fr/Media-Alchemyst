<?php

namespace MediaAlchemyst;

require_once __DIR__ . '/AbstractDriversContainerTest.php';

class DriversContainersTest extends AbstractDriversContainerTest
{
    public function getDrivers()
    {
        return new DriversContainer();
    }
}
