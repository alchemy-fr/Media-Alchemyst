<?php

namespace MediaAlchemyst\Specification;

class VideoTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @var Video
     */
    protected $object;

    protected function setUp()
    {
        $this->object = new Video;
    }

    /**
     * @covers MediaAlchemyst\Specification\Video::getType
     */
    public function testGetType()
    {
        $this->assertEquals(Specification::TYPE_VIDEO, $this->object->getType());
    }

    /**
     * @covers MediaAlchemyst\Specification\Video::setVideoCodec
     * @covers MediaAlchemyst\Specification\Video::getVideoCodec
     */
    public function testSetVideoCodec()
    {
        $this->object->setVideoCodec('Aubergine');
        $this->assertEquals('Aubergine', $this->object->getVideoCodec());
    }

    /**
     * @covers MediaAlchemyst\Specification\Video::setDimensions
     * @covers MediaAlchemyst\Specification\Video::getWidth
     * @covers MediaAlchemyst\Specification\Video::getHeight
     */
    public function testSetDimensions()
    {
        $this->object->setDimensions(480, 220);
        $this->assertEquals(480, $this->object->getWidth());
        $this->assertEquals(220, $this->object->getHeight());
    }

}
