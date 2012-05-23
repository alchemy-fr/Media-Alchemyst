<?php

namespace MediaAlchemyst\Transmuter;

class Video2ImageTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @var Video2Image
     */
    protected $object;

    /**
     *
     * @var \MediaAlchemyst\Specification\Image
     */
    protected $specs;
    protected $source;
    protected $dest;

    protected function setUp()
    {
        $this->object = new Video2Image(new \MediaAlchemyst\DriversContainer(new \Symfony\Component\DependencyInjection\ParameterBag\ParameterBag(array())));

        $this->specs = new \MediaAlchemyst\Specification\Image();
        $this->source = \MediaVorus\MediaVorus::guess(new \SplFileInfo(__DIR__ . '/../../../files/Test.ogv'));
        $this->dest = __DIR__ . '/../../../files/output_.png';
    }

    protected function tearDown()
    {
        if (file_exists($this->dest) && is_writable($this->dest)) {
            unlink($this->dest);
        }
    }

    /**
     * @covers MediaAlchemyst\Transmuter\Video2Image::execute
     */
    public function testExecute()
    {
        $this->object->execute($this->specs, $this->source, $this->dest);

        $mediaDest = \MediaVorus\MediaVorus::guess(new \SplFileInfo($this->dest));

        $this->assertEquals('image/png', $mediaDest->getFile()->getMimeType());
        $this->assertTrue(abs($this->source->getWidth() - $mediaDest->getWidth()) <= 16);
        $this->assertTrue(abs($this->source->getHeight() - $mediaDest->getHeight()) <= 16);
    }

    /**
     * @covers MediaAlchemyst\Transmuter\Video2Image::execute
     */
    public function testExecuteWithOptions()
    {
        $this->specs->setDimensions(320, 240);

        $this->object->execute($this->specs, $this->source, $this->dest);

        $mediaDest = \MediaVorus\MediaVorus::guess(new \SplFileInfo($this->dest));

        $this->assertEquals('image/png', $mediaDest->getFile()->getMimeType());
        $this->assertEquals(320, $mediaDest->getWidth());
        $this->assertEquals(240, $mediaDest->getHeight());
    }

    /**
     * @covers MediaAlchemyst\Transmuter\Video2Image::execute
     * @expectedException MediaAlchemyst\Exception\SpecNotSupportedException
     */
    public function testExecuteWrongSpecs()
    {
        $this->object->execute(new \MediaAlchemyst\Specification\Video(), $this->source, $this->dest);
    }

    /**
     * @dataProvider getTimeAndPercents
     * @covers MediaAlchemyst\Transmuter\Video2Image::parseTimeAsRatio
     */
    public function testParseTimeAsRatio($time, $percent)
    {
        $object = new Video2ImageExtended();

        $this->assertEquals($percent, $object->testparseTimeAsRatio($time));
    }

    public function getTimeAndPercents()
    {
        return array(
          array('30%', 0.3),
          array('100%', 1),
          array('0%', 0),
          array('0.5', 0.5),
        );
    }

}

class Video2ImageExtended extends Video2Image
{

    public function __construct()
    {

    }

    public function testparseTimeAsRatio($time)
    {
        return parent::parseTimeAsRatio($time);
    }

}
