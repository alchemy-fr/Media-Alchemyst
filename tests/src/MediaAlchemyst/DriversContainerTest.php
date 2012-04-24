<?php

namespace MediaAlchemyst;

use \Symfony\Component\DependencyInjection\ParameterBag\ParameterBag;

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
     * @covers MediaAlchemyst\DriversContainer::getFFMpeg
     */
    public function testGetFFMpeg()
    {
        $this->assertInstanceOf('\\FFMpeg\\FFMpeg', $this->object->getFFMpeg());
    }

    /**
     * @covers MediaAlchemyst\DriversContainer::getImagine
     */
    public function testGetImagine()
    {
        $this->assertInstanceOf('\\Imagine\\Image\\ImagineInterface', $this->object->getImagine());
    }

    /**
     * @covers MediaAlchemyst\DriversContainer::getPdf2Swf
     */
    public function testGetPdf2Swf()
    {
        $this->assertInstanceOf('\\SwfTools\\Binary\\Pdf2Swf', $this->object->getPdf2Swf());
    }

    /**
     * @covers MediaAlchemyst\DriversContainer::getSwfRender
     */
    public function testgetSwfRender()
    {
        $this->assertInstanceOf('\\SwfTools\\Binary\\SwfRender', $this->object->getSwfRender());
    }

    /**
     * @covers MediaAlchemyst\DriversContainer::getUnoconv
     */
    public function testgetUnoconv()
    {
        $this->assertInstanceOf('\\Unoconv\\Unoconv', $this->object->getUnoconv());
    }

    /**
     * @covers MediaAlchemyst\DriversContainer::getExiftoolExtractor
     */
    public function testgetExiftoolExtractor()
    {
        $this->assertInstanceOf('\\PHPExiftool\\PreviewExtractor', $this->object->getExiftoolExtractor());
    }

    /**
     * @covers MediaAlchemyst\DriversContainer::getMP4Box
     */
    public function testgetMP4Box()
    {
        $this->assertInstanceOf('\\MP4Box\\MP4Box', $this->object->getMP4Box());
    }

}
