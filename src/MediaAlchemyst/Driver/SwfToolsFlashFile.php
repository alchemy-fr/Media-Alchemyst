<?php

namespace MediaAlchemyst\Driver;

use Monolog\Logger;
use SwfTools\Configuration;
use SwfTools\Processor\FlashFile;

class SwfToolsFlashFile extends Provider
{
    protected $driver;

    public function __construct(Logger $logger, $swf_extract_binary = null, $swf_render_binary = null)
    {
        $this->logger = $logger;

        $conf = array();

        if ($swf_extract_binary) {
            $conf['swfextact'] = $swf_extract_binary;
        }
        if ($swf_render_binary) {
            $conf['swfrender'] = $swf_render_binary;
        }


        $this->driver = new FlashFile(new Configuration($conf), $this->logger);
    }

    public function getDriver()
    {
        return $this->driver;
    }
}
