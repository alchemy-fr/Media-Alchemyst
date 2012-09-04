<?php

namespace MediaAlchemyst\Driver;

use Monolog\Logger;

abstract class AsbtractDriver implements DriverInterface
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
