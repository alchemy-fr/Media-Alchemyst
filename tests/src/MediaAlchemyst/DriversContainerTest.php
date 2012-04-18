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

}
