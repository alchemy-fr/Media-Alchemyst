<?php

namespace MediaAlchemyst\Transmuter;

use MediaAlchemyst\AbstractAlchemystTester;
use MediaAlchemyst\DriversContainer;
use MediaAlchemyst\Specification\Audio;
use MediaAlchemyst\Specification\UnknownSpecs;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBag;

require_once __DIR__ . '/../AbstractAlchemystTester.php';
require_once __DIR__ . '/../Specification/UnknownSpecs.php';

class Audio2AudioTest extends AbstractAlchemystTester
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
        $this->object = new Audio2Audio(new DriversContainer(new ParameterBag(array())));
        $this->specs = new Audio();
        $this->source = $this->getMediaVorus()->guess(__DIR__ . '/../../../files/Audio.mp3');
        $this->dest = __DIR__ . '/../../../files/output_audio.flac';
    }

    public function tearDown()
    {
        if (file_exists($this->dest) && is_writable($this->dest)) {
            unlink($this->dest);
        }
    }

    /**
     * @covers MediaAlchemyst\Transmuter\Audio2Audio::execute
     */
    public function testExecute()
    {
        $this->object->execute($this->specs, $this->source, $this->dest);

        $mediaDest = $this->getMediaVorus()->guess($this->dest);

        $this->assertEquals('audio/x-flac', $mediaDest->getFile()->getMimeType());
        $this->assertEquals(round($this->source->getDuration()), round($mediaDest->getDuration()));
    }

    /**
     * @covers MediaAlchemyst\Transmuter\Audio2Audio::execute
     * @covers MediaAlchemyst\Transmuter\Audio2Audio::getFormatFromFileType
     */
    public function testExecuteWithOptions()
    {
        $this->specs->setAudioCodec('flac');
        $this->specs->setAudioSampleRate(96000);
        $this->specs->setKiloBitrate(256);

        $this->object->execute($this->specs, $this->source, $this->dest);

        $mediaDest = $this->getMediaVorus()->guess($this->dest);

        $this->assertEquals('audio/x-flac', $mediaDest->getFile()->getMimeType());
        $this->assertEquals(round($this->source->getDuration()), round($mediaDest->getDuration()));
    }

    /**
     * @covers MediaAlchemyst\Transmuter\Audio2Audio::execute
     */
    public function testExecuteMp3Type()
    {
        $this->dest = __DIR__ . '/../../../files/output_audio.mp3';

        $this->object->execute($this->specs, $this->source, $this->dest);

        $mediaDest = $this->getMediaVorus()->guess($this->dest);

        $this->assertEquals('audio/mpeg', $mediaDest->getFile()->getMimeType());
        $this->assertEquals(round($this->source->getDuration()), round($mediaDest->getDuration()));
    }

    /**
     * @covers MediaAlchemyst\Transmuter\Audio2Audio::execute
     * @covers MediaAlchemyst\Exception\SpecNotSupportedException
     * @expectedException \MediaAlchemyst\Exception\SpecNotSupportedException
     */
    public function testWrongSpecs()
    {
        $this->object->execute(new UnknownSpecs(), $this->source, $this->dest);
    }

}
