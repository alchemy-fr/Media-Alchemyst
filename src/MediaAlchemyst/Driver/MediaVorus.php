<?php

namespace MediaAlchemyst\Driver;

use Monolog\Logger;
use Imagine\Exception as ImagineException;
use MediaAlchemyst\Exception;

class MediaVorus extends Provider
{
    protected $driver;

    public function __construct(Logger $logger, $use_binary = null)
    {
        $this->logger = $logger;

        $exiftool = new \PHPExiftool\Exiftool();

        try {
        if ($use_binary) {
            $ffprobe = new \FFMpeg\FFProbe($use_binary, $this->logger);
        } else {
            $ffprobe = \FFMpeg\FFProbe::load($this->logger);
        }
        } catch(\FFMpeg\Exception\ExceptionInterface $e){
            throw new Exception\RuntimeException('Unable to load FFProbe for MediaVorus');
        }


        $this->driver = new \MediaVorus\MediaVorus(new \PHPExiftool\Reader($exiftool, new \PHPExiftool\RDFParser()), new \PHPExiftool\Writer($exiftool), $ffprobe);


        if ( ! $this->driver) {
            throw new Exception\RuntimeException('No driver available');
        }
    }

    public function getDriver()
    {
        return $this->driver;
    }
}
