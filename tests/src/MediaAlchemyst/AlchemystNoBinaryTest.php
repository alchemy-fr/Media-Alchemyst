<?php

namespace MediaAlchemyst;

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
        $driversContainer = new DriversContainer();

        $driversContainer['configuration'] = array(
            'ffmpeg.ffmpeg.binaries'       => 'nofile',
            'ffmpeg.ffprobe.binaries'      => 'nofile',
            'imagine.driver'               => 'nofile',
            'gs.binaries'                  => 'nofile',
            'mp4box.binaries'              => 'nofile',
            'swftools.pdf2swf.binaries'    => 'nofile',
            'swftools.swfrender.binaries'  => 'nofile',
            'swftools.swfextract.binaries' => 'nofile',
            'unoconv.binaries'             => 'nofile',
        );

        $this->object = new Alchemyst($driversContainer);

        $this->specsAudio = new Specification\Audio();
        $this->specsFlash = new Specification\Flash();
        $this->specsVideo = new Specification\Video();
        $this->specsVideo->setDimensions(320, 240);
        $this->specsImage = new Specification\Image();
        $this->specsImage->setDimensions(320, 240);
    }

    /**
     * @covers MediaAlchemyst\Exception\FileNotFoundException
     * @expectedException MediaAlchemyst\Exception\FileNotFoundException
     */
    public function testOpenUnknownFile()
    {
        $this->object->turnInto(__DIR__ . '/../../files/invalid.file', 'dest.mpg', $this->getMock('MediaAlchemyst\Specification\SpecificationInterface'));
    }

    /**
     * @covers MediaAlchemyst\Alchemyst::turnInto
     * @covers MediaAlchemyst\Alchemyst::routeAction
     * @expectedException MediaAlchemyst\Exception\RuntimeException
     */
    public function testTurnIntoAudioAudio()
    {
        $dest = __DIR__ . '/../../files/output.flac';

        $this->object->turnInto(__DIR__ . '/../../files/Audio.mp3', $dest, $this->specsAudio);
    }

    /**
     * @expectedException MediaAlchemyst\Exception\RuntimeException
     */
    public function testTurnIntoFlashImage()
    {
        $dest = __DIR__ . '/../../files/output.png';

        $this->object->turnInto(__DIR__ . '/../../files/flashfile.swf', $dest, $this->specsImage);
    }

    /**
     * @covers MediaAlchemyst\Alchemyst::turnInto
     * @covers MediaAlchemyst\Alchemyst::routeAction
     * @expectedException MediaAlchemyst\Exception\RuntimeException
     */
    public function testTurnIntoDocumentImage()
    {
        $dest = __DIR__ . '/../../files/output.png';

        $this->object->turnInto(__DIR__ . '/../../files/Hello.odt', $dest, $this->specsImage);
    }

    /**
     * @covers MediaAlchemyst\Alchemyst::turnInto
     * @covers MediaAlchemyst\Alchemyst::routeAction
     * @expectedException MediaAlchemyst\Exception\RuntimeException
     */
    public function testTurnIntoDocumentFlash()
    {
        $dest = __DIR__ . '/../../files/output.swf';

        $this->object->turnInto(__DIR__ . '/../../files/Hello.odt', $dest, $this->specsFlash);
    }

    /**
     * @covers MediaAlchemyst\Alchemyst::turnInto
     * @covers MediaAlchemyst\Alchemyst::routeAction
     * @expectedException MediaAlchemyst\Exception\RuntimeException
     */
    public function testTurnIntoVideoImage()
    {
        $dest = __DIR__ . '/../../files/output.png';

        $this->object->turnInto(__DIR__ . '/../../files/Test.ogv', $dest, $this->specsImage);
    }

    /**
     * @covers MediaAlchemyst\Alchemyst::turnInto
     * @covers MediaAlchemyst\Alchemyst::routeAction
     * @expectedException MediaAlchemyst\Exception\RuntimeException
     */
    public function testTurnIntoVideoVideo()
    {
        $dest = __DIR__ . '/../../files/output.webm';

        $this->object->turnInto(__DIR__ . '/../../files/Test.ogv', $dest, $this->specsVideo);
    }

}
