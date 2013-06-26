<?php

namespace MediaAlchemyst\Tests;

use Symfony\Component\Process\ExecutableFinder;

abstract class AbstractDriversContainerTest extends \PHPUnit_Framework_TestCase
{
    abstract public function getDrivers();

    /**
     * @covers MediaAlchemyst\DriversContainer
     */
    public function testGetFFMpeg()
    {
        $object = $this->getDrivers();
        $this->assertInstanceOf('\\FFMpeg\\FFMpeg', $object['ffmpeg.ffmpeg']);
    }

    /**
     * @covers MediaAlchemyst\DriversContainer
     */
    public function testGetImagine()
    {
        $object = $this->getDrivers();
        $this->assertInstanceOf('\\Imagine\\Image\\ImagineInterface', $object['imagine']);
    }

    /**
     * @covers MediaAlchemyst\DriversContainer
     */
    public function testgetFlashFile()
    {
        $object = $this->getDrivers();
        $this->assertInstanceOf('\\SwfTools\\Processor\\FlashFile', $object['swftools.flash-file']);
    }

    /**
     * @covers MediaAlchemyst\DriversContainer
     */
    public function testgetPDFFile()
    {
        $object = $this->getDrivers();
        $this->assertInstanceOf('\\SwfTools\\Processor\\PDFFile', $object['swftools.pdf-file']);
    }

    /**
     * @covers MediaAlchemyst\DriversContainer
     */
    public function testgetUnoconv()
    {
        $executableFinder = new ExecutableFinder();
        if (!$executableFinder->find('unoconv')) {
            $this->markTestSkipped('Unoconv is not installed');
        }
        $object = $this->getDrivers();

        $this->assertInstanceOf('\\Unoconv\\Unoconv', $object['unoconv']);
    }

    /**
     * @covers MediaAlchemyst\DriversContainer
     */
    public function testgetExiftoolExtractor()
    {
        $object = $this->getDrivers();
        $this->assertInstanceOf('\\PHPExiftool\\PreviewExtractor', $object['exiftool.preview-extractor']);
    }

    /**
     * @covers MediaAlchemyst\DriversContainer
     */
    public function testgetMP4Box()
    {
        $object = $this->getDrivers();
        $this->assertInstanceOf('\\MP4Box\\MP4Box', $object['mp4box']);
    }
}
