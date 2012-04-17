<?php

namespace MediAlchemyst\Transmuter;

require_once __DIR__ . '/../Specification/UnknownSpecs.php';

require_once dirname(__FILE__) . '/../../../../src/MediAlchemyst/Transmuter/Video2Video.php';

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
        $this->object = new Video2Video(new \MediAlchemyst\DriversContainer(new \Symfony\Component\DependencyInjection\ParameterBag\ParameterBag(array())));

        $this->specs = new \MediAlchemyst\Specification\Video();
        $this->specs->setDimensions(320, 240);
        $this->specs->setFileType(\MediAlchemyst\Specification\Video::FILETYPE_WEBM);

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
     * @covers MediAlchemyst\Transmuter\Video2Video::execute
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
     * @covers MediAlchemyst\Transmuter\Video2Video::execute
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
     * @covers MediAlchemyst\Transmuter\Video2Video::execute
     * @expectedException MediAlchemyst\Exception\SpecNotSupportedException
     */
    public function testExecuteWithBasSpecs()
    {
        $this->object->execute(new \MediAlchemyst\Specification\UnknownSpecs(), $this->source, $this->dest);
    }

    /**
     * @covers MediAlchemyst\Transmuter\Video2Video::getFormatFromFileType
     */
    public function testGetFormatFromFileType()
    {
        $Object = new Video2VideoExtended();

        $formats = array(
          \MediAlchemyst\Specification\Video::FILETYPE_WEBM,
          \MediAlchemyst\Specification\Video::FILETYPE_X264,
          \MediAlchemyst\Specification\Video::FILETYPE_OGG,
        );

        foreach ($formats as $fileType)
        {
            $Object->testgetFormatFromFileType($fileType, 200, 200);
        }
    }

    /**
     * @covers MediAlchemyst\Transmuter\Video2Video::getFormatFromFileType
     * @expectedException MediAlchemyst\Exception\FormatNotSupportedException
     */
    public function testGetFormatFromWrongFileType()
    {
        $Object = new Video2VideoExtended();

        $Object->testgetFormatFromFileType(\MediAlchemyst\Specification\Audio::FILETYPE_MP3, 200, 200);
    }

}

class Video2VideoExtended extends Video2Video
{

    public function __construct()
    {

    }

    public function testgetFormatFromFileType($fileType, $width, $height)
    {
        return parent::getFormatFromFileType($fileType, $width, $height);
    }

}