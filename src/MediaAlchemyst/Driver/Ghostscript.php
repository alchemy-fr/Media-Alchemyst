<?php

namespace MediaAlchemyst\Driver;

use Monolog\Logger;
use Ghostscript\Transcoder;
use Ghostscript\Exception\ExceptionInterface as GhostscriptException;
use MediaAlchemyst\Exception;

class Ghostscript extends Provider
{
    protected $driver;

    public function __construct(Logger $logger, $useBinary = null)
    {
        $this->logger = $logger;

        if ($useBinary) {
            $this->driver = new Transcoder($useBinary, $this->logger);
        } else {
            try {
                $this->driver = Transcoder::load($this->logger);
            } catch (GhostscriptException $e) {
                throw new Exception\RuntimeException('No driver available');
            }
        }
    }

    public function getDriver()
    {
        return $this->driver;
    }
}
