<?php

namespace MediaAlchemyst\Transmuter;

require_once __DIR__ . '/../Specification/UnknownSpecs.php';

class Video2VideoTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @var Video2Video
     */
    protected $object;
    protected $specs;
    protected $source;
    protected $dest;

    protected function setUp()
    {
        $this->object = new Video2Video(new \MediaAlchemyst\DriversContainer(new \Symfony\Component\DependencyInjection\ParameterBag\ParameterBag(array())));

        $this->specs = new \MediaAlchemyst\Specification\Video();
        $this->specs->setDimensions(320, 240);

        $this->source = \MediaVorus\MediaVorus::guess(new \SplFileInfo(__DIR__ . '/../../../files/Test.ogv'));
        $this->dest = __DIR__ . '/../../../files/output_video.webm';
    }

    protected function tearDown()
    {
        if (file_exists($this->dest) && is_writable($this->dest))
        {
            unlink($this->dest);
        }
    }

    /**
     * @covers MediaAlchemyst\Transmuter\Video2Video::execute
     * @todo Implement testExecute().
     */
    public function testExecute()
    {
        $this->object->execute($this->specs, $this->source, $this->dest);

        $mediaDest = \MediaVorus\MediaVorus::guess(new \SplFileInfo($this->dest));

        $this->assertEquals('video/webm', $mediaDest->getFile()->getMimeType());
        $this->assertEquals($this->source->getDuration(), $mediaDest->getDuration());
        $this->assertEquals(320, $mediaDest->getWidth());
        $this->assertEquals(240, $mediaDest->getHeight());
    }

    /**
     * @covers MediaAlchemyst\Transmuter\Video2Video::execute
     * @todo Implement testExecute().
     */
    public function testExecuteWithOptions()
    {
        $this->specs->setAudioCodec('libvorbis');
        $this->specs->setVideoCodec('libvpx');
        $this->specs->setAudioSampleRate(10025);
        $this->specs->setKiloBitrate(1000);

        $this->object->execute($this->specs, $this->source, $this->dest);

        $mediaDest = \MediaVorus\MediaVorus::guess(new \SplFileInfo($this->dest));

        $this->assertEquals('video/webm', $mediaDest->getFile()->getMimeType());
        $this->assertEquals($this->source->getDuration(), $mediaDest->getDuration());
        $this->assertEquals(320, $mediaDest->getWidth());
        $this->assertEquals(240, $mediaDest->getHeight());
    }

    /**
     * @covers MediaAlchemyst\Transmuter\Video2Video::execute
     * @covers MediaAlchemyst\Exception\SpecNotSupportedException
     * @expectedException MediaAlchemyst\Exception\SpecNotSupportedException
     */
    public function testExecuteWithBasSpecs()
    {
        $this->object->execute(new \MediaAlchemyst\Specification\UnknownSpecs(), $this->source, $this->dest);
    }

    /**
     * @dataProvider getFormats
     * @covers MediaAlchemyst\Transmuter\Video2Video::getFormatFromFileType
     */
    public function testGetFormatFromFileType($file, $instance)
    {
        $Object = new Video2VideoExtended();
        $this->assertInstanceOf($instance, $Object->testgetFormatFromFileType($file, 200, 200));
    }

    public function getFormats()
    {
        return array(
          array('file.ogv', '\\FFMpeg\\Format\\Video\\Ogg'),
          array('file.mp4', '\\FFMpeg\\Format\\Video\\X264'),
          array('file.webm', '\\FFMpeg\\Format\\Video\\WebM'),
        );
    }

    /**
     * @covers MediaAlchemyst\Transmuter\Video2Video::getFormatFromFileType
     * @covers MediaAlchemyst\Exception\FormatNotSupportedException
     * @expectedException MediaAlchemyst\Exception\FormatNotSupportedException
     */
    public function testGetFormatFromWrongFileType()
    {
        $Object = new Video2VideoExtended();

        $Object->testgetFormatFromFileType('out.jpg', 200, 200);
    }

}

class Video2VideoExtended extends Video2Video
{

    public function __construct()
    {

    }

    public function testgetFormatFromFileType($dest, $width, $height)
    {
        return parent::getFormatFromFileType($dest, $width, $height);
    }

}