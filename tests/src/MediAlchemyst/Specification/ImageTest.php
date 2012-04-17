<?php

namespace MediAlchemyst\Specification;

class ImageTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @var Image
     */
    protected $object;


    /**
     * @covers MediAlchemyst\Specification\Image::__construct
     */
    protected function setUp()
    {
        $this->object = new Image();
    }

    /**
     * @covers MediAlchemyst\Specification\Image::getType
     */
    public function testGetType()
    {
        $this->assertEquals(Specification::TYPE_IMAGE, $this->object->getType());
    }

    /**
     * @covers MediAlchemyst\Specification\Image::setDimensions
     * @covers MediAlchemyst\Specification\Image::getWidth
     * @covers MediAlchemyst\Specification\Image::getHeight
     */
    public function testSetDimensions()
    {
        $this->object->setDimensions(320, 240);
        $this->assertEquals(320, $this->object->getWidth());
        $this->assertEquals(240, $this->object->getHeight());
    }

    /**
     * @covers MediAlchemyst\Specification\Image::setResizeMode
     * @covers MediAlchemyst\Specification\Image::getResizeMode
     */
    public function testSetResizeMode()
    {
        $this->assertEquals(Image::RESIZE_MODE_INBOUND, $this->object->getResizeMode());
        $this->object->setResizeMode(Image::RESIZE_MODE_OUTBOUND);
        $this->assertEquals(Image::RESIZE_MODE_OUTBOUND, $this->object->getResizeMode());
    }

    /**
     * @covers MediAlchemyst\Specification\Image::setResizeMode
     * @expectedException MediAlchemyst\Exception\InvalidArgumentException
     */
    public function testSetResizeModeFail()
    {
        $this->object->setResizeMode('+Agauche');
    }

    /**
     * @covers MediAlchemyst\Specification\Image::setRotationAngle
     * @covers MediAlchemyst\Specification\Image::getRotationAngle
     */
    public function testSetRotationAngle()
    {
        $this->object->setRotationAngle(90);
        $this->assertEquals(90, $this->object->getRotationAngle());
    }

    /**
     * @covers MediAlchemyst\Specification\Image::setStrip
     * @covers MediAlchemyst\Specification\Image::getStrip
     */
    public function testSetStrip()
    {
        $this->object->setStrip(true);
        $this->assertEquals(true, $this->object->getStrip());
        $this->object->setStrip(false);
        $this->assertEquals(false, $this->object->getStrip());
    }

}
