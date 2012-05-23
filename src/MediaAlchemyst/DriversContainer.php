<?php

namespace MediaAlchemyst;

use MediaAlchemyst\Driver;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBag;

class DriversContainer extends \Pimple
{

    public function __construct(ParameterBag $configuration, \Monolog\Logger $logger = null)
    {
        if ( ! $logger) {
            $logger = new \Monolog\Logger('Drivers');
            $logger->pushHandler(new \Monolog\Handler\NullHandler());
        }

        $this['FFMpeg'] = $this->share(function() use ($configuration, $logger) {
              $ffmpeg = $configuration->has('ffmpeg') ? $configuration->get('ffmpeg') : null;

              $driver = new Driver\FFMpeg($logger, $ffmpeg);

              return $driver->getDriver();
          });

        $this['Imagine'] = $this->share(function() use ($configuration, $logger) {
              $imagine = $configuration->has('imagine') ? $configuration->get('imagine') : null;

              $driver = new Driver\Imagine($logger, $imagine);

              return $driver->getDriver();
          });

        $this['SwfRender'] = $this->share(function() use ($configuration, $logger) {
              $SwfRender = $configuration->has('SwfRender') ? $configuration->get('SwfRender') : null;

              $driver = new Driver\SwfRender($logger, $SwfRender);

              return $driver->getDriver();
          });

        $this['Pdf2Swf'] = $this->share(function() use ($configuration, $logger) {
              $SwfRender = $configuration->has('Pdf2Swf') ? $configuration->get('Pdf2Swf') : null;

              $driver = new Driver\Pdf2Swf($logger, $SwfRender);

              return $driver->getDriver();
          });

        $this['Unoconv'] = $this->share(function() use ($configuration, $logger) {
              $unoconv = $configuration->has('Unoconv') ? $configuration->get('Unoconv') : null;

              $driver = new Driver\Unoconv($logger, $unoconv);

              return $driver->getDriver();
          });

        $this['ExiftoolExtractor'] = $this->share(function() use ($configuration, $logger) {
              $driver = new Driver\ExiftoolExtractor($logger, null);

              return $driver->getDriver();
          });

        $this['MP4Box'] = $this->share(function() use ($configuration, $logger) {
              $MP4Box = $configuration->has('MP4Box') ? $configuration->get('MP4Box') : null;

              $driver = new Driver\MP4Box($logger, $MP4Box);

              return $driver->getDriver();
          });
    }

    /**
     *
     * @return \FFMpeg\FFMpeg
     */
    public function getFFMpeg()
    {
        return $this['FFMpeg'];
    }

    /**
     *
     * @return \Imagine\Image\ImagineInterface
     */
    public function getImagine()
    {
        return $this['Imagine'];
    }

    /**
     *
     * @return \SwfTools\Binary\Swfrender
     */
    public function getSwfRender()
    {
        return $this['SwfRender'];
    }

    /**
     *
     * @return \SwfTools\Binary\Pdf2Swf
     */
    public function getPdf2Swf()
    {
        return $this['Pdf2Swf'];
    }

    /**
     *
     * @return \Unoconv\Unoconv
     */
    public function getUnoconv()
    {
        return $this['Unoconv'];
    }

    /**
     *
     * @return \PHPExiftool\PreviewExtractor
     */
    public function getExiftoolExtractor()
    {
        return $this['ExiftoolExtractor'];
    }

    /**
     *
     * @return \MP4Box\MP4Box
     */
    public function getMP4Box()
    {
        return $this['MP4Box'];
    }

}
