<?php

namespace MediAlchemyst\Specification;

class AudioTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @var Audio
     */
    protected $object;

    /**
     * @covers MediAlchemyst\Specification\Audio::__construct
     */
    protected function setUp()
    {
        $this->object = new Audio();
    }

    /**
     * @covers MediAlchemyst\Specification\Audio::getType
     */
    public function testGetType()
    {
        $this->assertEquals(Specification::TYPE_AUDIO, $this->object->getType());
    }

    /**
     * @covers MediAlchemyst\Specification\Audio::setFileType
     * @covers MediAlchemyst\Specification\Audio::getFileType
     */
    public function testGetFileType()
    {
        $this->object->setFileType(Audio::FILETYPE_FLAC);
        $this->assertEquals(Audio::FILETYPE_FLAC, $this->object->getFileType());
        $this->object->setFileType(Audio::FILETYPE_MP3);
        $this->assertEquals(Audio::FILETYPE_MP3, $this->object->getFileType());
    }

    /**
     * @covers MediAlchemyst\Specification\Audio::setFileType
     * @expectedException MediAlchemyst\Exception\InvalidArgumentException
     */
    public function testSetWrongFileType()
    {
        $this->object->setFileType('Tarte aux pommes');
    }

    /**
     * @covers MediAlchemyst\Specification\Audio::setKiloBitrate
     * @covers MediAlchemyst\Specification\Audio::getKiloBitrate
     */
    public function testSetKiloBitrate()
    {
        $this->object->setKiloBitrate(200);
        $this->assertEquals(200, $this->object->getKiloBitrate());
    }

    /**
     * @covers MediAlchemyst\Specification\Audio::setAudioCodec
     * @covers MediAlchemyst\Specification\Audio::getAudioCodec
     */
    public function testSetAudioCodec()
    {
        $this->object->setAudioCodec('Carlos');
        $this->assertEquals('Carlos', $this->object->getAudioCodec());
    }

    /**
     * @covers MediAlchemyst\Specification\Audio::setAudioSampleRate
     * @covers MediAlchemyst\Specification\Audio::getAudioSampleRate
     */
    public function testSetAudioSampleRate()
    {
        $this->object->setAudioSampleRate(22050);
        $this->assertEquals(22050, $this->object->getAudioSampleRate());
    }

}
