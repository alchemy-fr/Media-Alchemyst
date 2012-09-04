<?php

namespace MediaAlchemyst\Driver;

use Monolog\Logger;

abstract class AbstractDriver implements DriverInterface
{

    /**
     *
     * @var \Monolog\Logger
     */
    protected $logger;

    public function setLogger(Logger $logger)
    {
        $this->logger = $logger;
    }

}
