<?php

namespace MediaAlchemyst;

use \Symfony\Component\DependencyInjection\ParameterBag\ParameterBag;

class AlchemystNoBinaryTest extends \PHPUnit_Framework_TestCase
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
        $drivers = array(
          'unoconv'    => 'nofile',
          'mp4box'     => 'nofile',
          'ffmpeg'     => 'nofile',
          'ffprobe'    => 'nofile',
          'exiftool'   => 'nofile',
          'pdf2swf'    => 'nofile',
          'swfrender'  => 'nofile',
          'swfextract' => 'nofile',
        );

        $driversContainer = new DriversContainer(new ParameterBag($drivers));

        $this->object = new Alchemyst($driversContainer);

        $this->specsAudio = new Specification\Audio();
        $this->specsFlash = new Specification\Flash();
        $this->specsVideo = new Specification\Video();
        $this->specsVideo->setDimensions(320, 240);
        $this->specsImage = new Specification\Image();
        $this->specsImage->setDimensions(320, 240);
    }

    /**
     * @covers MediaAlchemyst\Alchemyst::open
     * @covers MediaAlchemyst\Alchemyst::close
     * @expectedException MediaAlchemyst\Exception\RuntimeException
     */
    public function testOpen()
    {
        $this->object->open(__DIR__ . '/../../files/ExifTool.jpg');
        $this->object->close();
        $this->object->open(__DIR__ . '/../../files/ExifTool.jpg');
        $this->object->open(__DIR__ . '/../../files/photo03.JPG');
    }

    /**
     * @covers MediaAlchemyst\Alchemyst::open
     * @covers MediaAlchemyst\Exception\FileNotFoundException
     * @expectedException MediaAlchemyst\Exception\RuntimeException
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
        $specs = new Specification\Audio();

        $this->object->turnInto(__DIR__ . '/../../files/output', $specs);
    }

    /**
     * @covers MediaAlchemyst\Alchemyst::turnInto
     * @covers MediaAlchemyst\Alchemyst::routeAction
     * @expectedException MediaAlchemyst\Exception\RuntimeException
     */
    public function testTurnIntoAudioAudio()
    {
        $this->object->open(__DIR__ . '/../../files/Audio.mp3');

        $dest = __DIR__ . '/../../files/output.flac';

        $this->object->turnInto($dest, $this->specsAudio);
    }

    /**
     * @covers MediaAlchemyst\Alchemyst::turnInto
     * @covers MediaAlchemyst\Alchemyst::routeAction
     * @expectedException MediaAlchemyst\Exception\RuntimeException
     */
    public function testTurnIntoFlashImage()
    {
        $this->object->open(__DIR__ . '/../../files/flashfile.swf');

        $dest = __DIR__ . '/../../files/output.png';

        $this->object->turnInto($dest, $this->specsImage);
    }

    /**
     * @covers MediaAlchemyst\Alchemyst::turnInto
     * @covers MediaAlchemyst\Alchemyst::routeAction
     * @expectedException MediaAlchemyst\Exception\RuntimeException
     */
    public function testTurnIntoDocumentImage()
    {
        $this->object->open(__DIR__ . '/../../files/Hello.odt');

        $dest = __DIR__ . '/../../files/output.png';

        $this->object->turnInto($dest, $this->specsImage);
    }

    /**
     * @covers MediaAlchemyst\Alchemyst::turnInto
     * @covers MediaAlchemyst\Alchemyst::routeAction
     * @expectedException MediaAlchemyst\Exception\RuntimeException
     */
    public function testTurnIntoDocumentFlash()
    {
        $this->object->open(__DIR__ . '/../../files/Hello.odt');

        $dest = __DIR__ . '/../../files/output.swf';

        $this->object->turnInto($dest, $this->specsFlash);
    }

    /**
     * @covers MediaAlchemyst\Alchemyst::turnInto
     * @covers MediaAlchemyst\Alchemyst::routeAction
     * @expectedException MediaAlchemyst\Exception\RuntimeException
     */
    public function testTurnIntoVideoImage()
    {
        $this->object->open(__DIR__ . '/../../files/Test.ogv');

        $dest = __DIR__ . '/../../files/output.png';

        $this->object->turnInto($dest, $this->specsImage);
    }

    /**
     * @covers MediaAlchemyst\Alchemyst::turnInto
     * @covers MediaAlchemyst\Alchemyst::routeAction
     * @expectedException MediaAlchemyst\Exception\RuntimeException
     */
    public function testTurnIntoVideoVideo()
    {
        $this->object->open(__DIR__ . '/../../files/Test.ogv');

        $dest = __DIR__ . '/../../files/output.webm';

        $this->object->turnInto($dest, $this->specsVideo);
    }

}
