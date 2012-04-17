<?php

namespace MediAlchemyst\Specification;

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
     * @covers MediAlchemyst\Specification\Video::getType
     */
    public function testGetType()
    {
        $this->assertEquals(Specification::TYPE_VIDEO, $this->object->getType());
    }

    /**
     * @covers MediAlchemyst\Specification\Video::setFileType
     * @covers MediAlchemyst\Specification\Video::getFileType
     */
    public function testSetFileType()
    {
        $this->object->setFileType(Video::FILETYPE_WEBM);
        $this->assertEquals(Video::FILETYPE_WEBM, $this->object->getFileType());
        $this->object->setFileType(Video::FILETYPE_X264);
        $this->assertEquals(Video::FILETYPE_X264, $this->object->getFileType());
    }

    /**
     * @covers MediAlchemyst\Specification\Video::setFileType
     * @covers MediAlchemyst\Exception\InvalidArgumentException
     * @expectedException MediAlchemyst\Exception\InvalidArgumentException
     */
    public function testSetWrongFileType()
    {
        $this->object->setFileType(Video::FILETYPE_FLAC);
    }

    /**
     * @covers MediAlchemyst\Specification\Video::setVideoCodec
     * @covers MediAlchemyst\Specification\Video::getVideoCodec
     */
    public function testSetVideoCodec()
    {
        $this->object->setVideoCodec('Aubergine');
        $this->assertEquals('Aubergine', $this->object->getVideoCodec());
    }

    /**
     * @covers MediAlchemyst\Specification\Video::setDimensions
     * @covers MediAlchemyst\Specification\Video::getWidth
     * @covers MediAlchemyst\Specification\Video::getHeight
     */
    public function testSetDimensions()
    {
        $this->object->setDimensions(480, 220);
        $this->assertEquals(480, $this->object->getWidth());
        $this->assertEquals(220, $this->object->getHeight());
    }

}
