<?php

namespace MediAlchemyst\Transmuter;

require_once __DIR__ . '/../Specification/UnknownSpecs.php';

class Audio2AudioTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @var Audio2Audio
     */
    protected $object;
    protected $specs;
    protected $source;
    protected $dest;

    protected function setUp()
    {
        $this->object = new Audio2Audio(new \MediAlchemyst\DriversContainer(new \Symfony\Component\DependencyInjection\ParameterBag\ParameterBag(array())));

        $this->specs = new \MediAlchemyst\Specification\Audio();

        $this->source = \MediaVorus\MediaVorus::guess(new \SplFileInfo(__DIR__ . '/../../../files/Audio.mp3'));
        $this->dest = __DIR__ . '/../../../files/output_audio.flac';
    }

    public function tearDown()
    {
        if (file_exists($this->dest) && is_writable($this->dest))
        {
            unlink($this->dest);
        }
    }

    /**
     * @covers MediAlchemyst\Transmuter\Audio2Audio::execute
     */
    public function testExecute()
    {
        $this->object->execute($this->specs, $this->source, $this->dest);

        $mediaDest = \MediaVorus\MediaVorus::guess(new \SplFileInfo($this->dest));

        $this->assertEquals('audio/x-flac', $mediaDest->getFile()->getMimeType());
        $this->assertEquals($this->source->getDuration(), $mediaDest->getDuration());
    }

    /**
     * @covers MediAlchemyst\Transmuter\Audio2Audio::execute
     */
    public function testExecuteWithOptions()
    {
        $this->specs->setAudioCodec('flac');
        $this->specs->setAudioSampleRate(96000);
        $this->specs->setKiloBitrate(256);

        $this->object->execute($this->specs, $this->source, $this->dest);

        $mediaDest = \MediaVorus\MediaVorus::guess(new \SplFileInfo($this->dest));

        $this->assertEquals('audio/x-flac', $mediaDest->getFile()->getMimeType());
        $this->assertEquals($this->source->getDuration(), $mediaDest->getDuration());
    }

    /**
     * @covers MediAlchemyst\Transmuter\Audio2Audio::execute
     */
    public function testExecuteMp3Type()
    {
        $this->dest = __DIR__ . '/../../../files/output_audio.mp3';

        $this->object->execute($this->specs, $this->source, $this->dest);

        $mediaDest = \MediaVorus\MediaVorus::guess(new \SplFileInfo($this->dest));

        $this->assertEquals('audio/mpeg', $mediaDest->getFile()->getMimeType());
        $this->assertEquals($this->source->getDuration(), $mediaDest->getDuration());
    }

    /**
     * @covers MediAlchemyst\Transmuter\Audio2Audio::execute
     * @covers MediAlchemyst\Exception\SpecNotSupportedException
     * @expectedException \MediAlchemyst\Exception\SpecNotSupportedException
     */
    public function testWrongSpecs()
    {
        $this->object->execute(new \MediAlchemyst\Specification\UnknownSpecs(), $this->source, $this->dest);
    }

}
