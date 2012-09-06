<?php

namespace MediaAlchemyst;

use MediaAlchemyst\Specification\Audio;
use MediaAlchemyst\Specification\Image;
use MediaAlchemyst\Specification\Flash;
use MediaAlchemyst\Specification\Video;
use MediaVorus\Media\MediaInterface;
use Symfony\Component\Process\ExecutableFinder;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBag;

require_once __DIR__ . '/AbstractAlchemystTester.php';

class AlchemystTest extends AbstractAlchemystTester
{

    /**
     * @var Alchemyst
     */
    protected $object;
    protected $specsAudio;
    protected $specsFlash;
    protected $specsImage;
    protected $specsVideo;

    /**
     * @covers MediaAlchemyst\Alchemyst::__construct
     */
    protected function setUp()
    {
        $this->object = new Alchemyst(new DriversContainer(new ParameterBag(array())));

        $this->specsAudio = new Audio();
        $this->specsFlash = new Flash();
        $this->specsVideo = new Video();
        $this->specsVideo->setDimensions(320, 240);
        $this->specsImage = new Image();
        $this->specsImage->setDimensions(320, 240);
    }

    /**
     * @covers MediaAlchemyst\Alchemyst::open
     * @covers MediaAlchemyst\Alchemyst::close
     */
    public function testOpen()
    {
        $this->object->open(__DIR__ . '/../../files/Audio.mp3');
        $this->object->close();
        $this->object->open(__DIR__ . '/../../files/Audio.mp3');
        $this->object->open(__DIR__ . '/../../files/Test.ogv');
    }

    /**
     * @covers MediaAlchemyst\Alchemyst::open
     * @covers MediaAlchemyst\Exception\FileNotFoundException
     * @expectedException MediaAlchemyst\Exception\FileNotFoundException
     */
    public function testOpenUnknownFile()
    {
        $this->object->open(__DIR__ . '/../../files/invalid.file');
    }

    /**
     * @covers MediaAlchemyst\Alchemyst::turnInto
     * @covers MediaAlchemyst\Exception\LogicException
     * @expectedException MediaAlchemyst\Exception\LogicException
     */
    public function testTurnIntoNoFile()
    {
        $specs = new Audio();

        $this->object->turnInto(__DIR__ . '/../../files/output', $specs);
    }

    /**
     * @covers MediaAlchemyst\Alchemyst::turnInto
     * @covers MediaAlchemyst\Alchemyst::routeAction
     */
    public function testTurnIntoAudioAudio()
    {
        $this->object->open(__DIR__ . '/../../files/Audio.mp3');

        $dest = __DIR__ . '/../../files/output.flac';

        $this->object->turnInto($dest, $this->specsAudio);

        $media = $this->getMediaVorus()->guess($dest);
        $this->assertEquals(MediaInterface::TYPE_AUDIO, $media->getType());

        unlink($dest);

        $this->object->close();
    }

    /**
     * @covers MediaAlchemyst\Alchemyst::turnInto
     * @covers MediaAlchemyst\Alchemyst::routeAction
     */
    public function testTurnIntoFlashImage()
    {
        $this->object->open(__DIR__ . '/../../files/flashfile.swf');

        $dest = __DIR__ . '/../../files/output.png';

        $this->object->turnInto($dest, $this->specsImage);

        $media = $this->getMediaVorus()->guess($dest);
        $this->assertEquals(MediaInterface::TYPE_IMAGE, $media->getType());

        unlink($dest);

        $this->object->close();
    }

    /**
     * @covers MediaAlchemyst\Alchemyst::turnInto
     * @covers MediaAlchemyst\Alchemyst::routeAction
     */
    public function testTurnIntoDocumentImage()
    {
        $executableFinder = new ExecutableFinder();
        if ( ! $executableFinder->find('unoconv')) {
            $this->markTestSkipped('Unoconv is not installed');
        }

        $this->object->open(__DIR__ . '/../../files/Hello.odt');

        $dest = __DIR__ . '/../../files/output.png';

        $this->object->turnInto($dest, $this->specsImage);

        $media = $this->getMediaVorus()->guess($dest);
        $this->assertEquals(MediaInterface::TYPE_IMAGE, $media->getType());

        unlink($dest);

        $this->object->close();
    }

    /**
     * @covers MediaAlchemyst\Alchemyst::turnInto
     * @covers MediaAlchemyst\Alchemyst::routeAction
     */
    public function testTurnIntoDocumentFlash()
    {
        $executableFinder = new ExecutableFinder();
        if ( ! $executableFinder->find('unoconv')) {
            $this->markTestSkipped('Unoconv is not installed');
        }

        $this->object->open(__DIR__ . '/../../files/Hello.odt');

        $dest = __DIR__ . '/../../files/output.swf';

        $this->object->turnInto($dest, $this->specsFlash);

        $media = $this->getMediaVorus()->guess($dest);
        $this->assertEquals(MediaInterface::TYPE_FLASH, $media->getType());

        unlink($dest);

        $this->object->close();
    }

    /**
     * @covers MediaAlchemyst\Alchemyst::turnInto
     * @covers MediaAlchemyst\Alchemyst::routeAction
     */
    public function testTurnIntoImageImage()
    {
        $this->object->open(__DIR__ . '/../../files/photo03.JPG');

        $dest = __DIR__ . '/../../files/output.png';

        $this->object->turnInto($dest, $this->specsImage);

        $media = $this->getMediaVorus()->guess($dest);
        $this->assertEquals(MediaInterface::TYPE_IMAGE, $media->getType());

        unlink($dest);

        $this->object->close();
    }

    /**
     * @covers MediaAlchemyst\Alchemyst::turnInto
     * @covers MediaAlchemyst\Alchemyst::routeAction
     */
    public function testTurnIntoVideoImage()
    {
        $this->object->open(__DIR__ . '/../../files/Test.ogv');

        $dest = __DIR__ . '/../../files/output.png';

        $this->object->turnInto($dest, $this->specsImage);

        $media = $this->getMediaVorus()->guess($dest);
        $this->assertEquals(MediaInterface::TYPE_IMAGE, $media->getType());

        unlink($dest);

        $this->object->close();
    }

    /**
     * @covers MediaAlchemyst\Alchemyst::turnInto
     * @covers MediaAlchemyst\Alchemyst::routeAction
     */
    public function testTurnIntoVideoVideo()
    {
        $this->object->open(__DIR__ . '/../../files/Test.ogv');

        $dest = __DIR__ . '/../../files/output.webm';

        $this->object->turnInto($dest, $this->specsVideo);

        $media = $this->getMediaVorus()->guess($dest);
        $this->assertEquals(MediaInterface::TYPE_VIDEO, $media->getType());

        unlink($dest);

        $this->object->close();
    }

}
