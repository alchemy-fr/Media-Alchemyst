<?php

namespace MediaAlchemyst;

use Symfony\Component\Process\ExecutableFinder;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBag;

class DriversContainerTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @var DriversContainer
     */
    protected $object;

    /**
     * @covers MediaAlchemyst\DriversContainer::__construct
     */
    protected function setUp()
    {
        $this->object = new DriversContainer(new ParameterBag());
    }

    /**
     * @covers MediaAlchemyst\DriversContainer
     */
    public function testGetFFMpeg()
    {
        $this->assertInstanceOf('\\FFMpeg\\FFMpeg', $this->object['ffmpeg.ffmpeg']);
    }

    /**
     * @covers MediaAlchemyst\DriversContainer
     */
    public function testGetImagine()
    {
        $this->assertInstanceOf('\\Imagine\\Image\\ImagineInterface', $this->object['imagine']);
    }

    /**
     * @covers MediaAlchemyst\DriversContainer
     */
    public function testGetPdf2Swf()
    {
        $this->assertInstanceOf('\\SwfTools\\Binary\\Pdf2Swf', $this->object['xpdf.pdf2swf']);
    }

    /**
     * @covers MediaAlchemyst\DriversContainer
     */
    public function testgetSwfRender()
    {
        $this->assertInstanceOf('\\SwfTools\\Processor\\FlashFile', $this->object['swftools.flash-file']);
    }

    /**
     * @covers MediaAlchemyst\DriversContainer
     */
    public function testgetUnoconv()
    {
        $executableFinder = new ExecutableFinder();
        if ( ! $executableFinder->find('unoconv')) {
            $this->markTestSkipped('Unoconv is not installed');
        }

        $this->assertInstanceOf('\\Unoconv\\Unoconv', $this->object['unoconv']);
    }

    /**
     * @covers MediaAlchemyst\DriversContainer
     */
    public function testgetExiftoolExtractor()
    {
        $this->assertInstanceOf('\\PHPExiftool\\PreviewExtractor', $this->object['exiftool.preview-extractor']);
    }

    /**
     * @covers MediaAlchemyst\DriversContainer
     */
    public function testgetMP4Box()
    {
        $this->assertInstanceOf('\\MP4Box\\MP4Box', $this->object['mp4box']);
    }

}
