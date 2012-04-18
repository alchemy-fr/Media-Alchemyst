<?php

namespace MediaAlchemyst\Specification;

class ImageTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @var Image
     */
    protected $object;


    /**
     * @covers MediaAlchemyst\Specification\Image::__construct
     */
    protected function setUp()
    {
        $this->object = new Image();
    }

    /**
     * @covers MediaAlchemyst\Specification\Image::getType
     */
    public function testGetType()
    {
        $this->assertEquals(Specification::TYPE_IMAGE, $this->object->getType());
    }

    /**
     * @covers MediaAlchemyst\Specification\Image::setDimensions
     * @covers MediaAlchemyst\Specification\Image::getWidth
     * @covers MediaAlchemyst\Specification\Image::getHeight
     */
    public function testSetDimensions()
    {
        $this->object->setDimensions(320, 240);
        $this->assertEquals(320, $this->object->getWidth());
        $this->assertEquals(240, $this->object->getHeight());
    }

    /**
     * @covers MediaAlchemyst\Specification\Image::setResizeMode
     * @covers MediaAlchemyst\Specification\Image::getResizeMode
     */
    public function testSetResizeMode()
    {
        $this->assertEquals(Image::RESIZE_MODE_INBOUND, $this->object->getResizeMode());
        $this->object->setResizeMode(Image::RESIZE_MODE_OUTBOUND);
        $this->assertEquals(Image::RESIZE_MODE_OUTBOUND, $this->object->getResizeMode());
    }

    /**
     * @covers MediaAlchemyst\Specification\Image::setResizeMode
     * @covers MediaAlchemyst\Exception\InvalidArgumentException
     * @expectedException MediaAlchemyst\Exception\InvalidArgumentException
     */
    public function testSetResizeModeFail()
    {
        $this->object->setResizeMode('+Agauche');
    }

    /**
     * @covers MediaAlchemyst\Specification\Image::setRotationAngle
     * @covers MediaAlchemyst\Specification\Image::getRotationAngle
     */
    public function testSetRotationAngle()
    {
        $this->object->setRotationAngle(90);
        $this->assertEquals(90, $this->object->getRotationAngle());
    }

    /**
     * @covers MediaAlchemyst\Specification\Image::setStrip
     * @covers MediaAlchemyst\Specification\Image::getStrip
     */
    public function testSetStrip()
    {
        $this->object->setStrip(true);
        $this->assertEquals(true, $this->object->getStrip());
        $this->object->setStrip(false);
        $this->assertEquals(false, $this->object->getStrip());
    }

}
