<?php

namespace MediaAlchemyst\Driver;

use FFMpeg\Exception\BinaryNotFoundException;
use FFMpeg\FFProbe;
use Monolog\Logger;
use MediaAlchemyst\Exception\RuntimeException;
use MediaVorus\MediaVorus as MediaVorusDriver;
use PHPExiftool\Exiftool;
use PHPExiftool\Reader;
use PHPExiftool\RDFParser;
use PHPExiftool\Writer;

class MediaVorus extends Provider
{
    protected $driver;

    public function __construct(Logger $logger, $use_binary = null)
    {
        $this->logger = $logger;

        $exiftool = new Exiftool();

        try {
            if ($use_binary) {
                $ffprobe = new FFProbe($use_binary, $this->logger);
            } else {
                $ffprobe = FFProbe::load($this->logger);
            }
        } catch (BinaryNotFoundException $e) {
            throw new RuntimeException('Unable to load FFProbe for MediaVorus');
        }

        $this->driver = new MediaVorusDriver(new Reader($exiftool, new RDFParser()), new Writer($exiftool), $ffprobe);
    }

    public function getDriver()
    {
        return $this->driver;
    }
}
