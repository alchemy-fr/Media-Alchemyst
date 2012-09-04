<?php

namespace MediaAlchemyst\Driver;

use Monolog\Logger;
use Unoconv\Unoconv;
use Unoconv\Exception\RuntimeException as UnoconvRuntimeException;
use MediaAlchemyst\Exception\RuntimeException;

class Unoconv extends Provider
{
    protected $driver;

    public function __construct(Logger $logger, $use_binary = null)
    {
        $this->logger = $logger;

        try {
            if ($use_binary) {
                $this->driver = new Unoconv($use_binary, $logger);
            } else {
                $this->driver = UnoconvBinary::load($logger);
            }
        } catch (UnoconvRuntimeException $e) {
            throw new RuntimeException('No driver available');
        }
    }

    public function getDriver()
    {
        return $this->driver;
    }
}
