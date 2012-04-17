<?php

namespace MediAlchemyst\Driver;

use Monolog\Logger;

abstract class Provider implements Driver
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
