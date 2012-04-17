<?php

namespace MediAlchemyst\Driver;

abstract class Provider implements Driver
{

    protected $logger;

    public function setLogger(\Monolog\Logger $logger)
    {
        $this->logger = $logger;
    }

}
