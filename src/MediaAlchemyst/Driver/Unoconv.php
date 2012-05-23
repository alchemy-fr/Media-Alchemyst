<?php

namespace MediaAlchemyst\Driver;

use Monolog\Logger;
use Unoconv\Unoconv as UnoconvBinary;
use MediaAlchemyst\Exception;

class Unoconv extends Provider
{

    protected $driver;

    public function __construct(Logger $logger, $use_binary = null)
    {
        $this->logger = $logger;

        if ($use_binary) {
            $this->driver = new UnoconvBinary($use_binary, $logger);
        } else {
            try {
                $this->driver = UnoconvBinary::load($logger);
            } catch (Exception\BinaryNotFoundException $e) {
                throw new Exception\RuntimeException('No driver available');
            }
        }
    }

    public function getDriver()
    {
        return $this->driver;
    }

}
