<?php

namespace MediaAlchemyst\Transmuter;

require_once __DIR__ . '/../Specification/UnknownSpecs.php';

class Image2ImageTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Image2Image
     */
    protected $object;
    protected $specs;
    protected $source;
    protected $dest;

    protected function setUp()
    {
        $this->object = new Image2Image(new \MediaAlchemyst\DriversContainer(new \Symfony\Component\DependencyInjection\ParameterBag\ParameterBag(array())));

        Image2Image::$autorotate = false;

        $this->specs = new \MediaAlchemyst\Specification\Image();
        $this->source = \MediaVorus\MediaVorus::guess(new \SplFileInfo(__DIR__ . '/../../../files/photo03.JPG'));
        $this->dest = __DIR__ . '/../../../files/output_auto_rotate.jpg';
    }

    public function tearDown()
    {
        if (file_exists($this->dest) && is_writable($this->dest)) {
            unlink($this->dest);
        }
    }

    /**
     * @covers MediaAlchemyst\Transmuter\Image2Image::execute
     */
    public function testExecute()
    {
        $this->object->execute($this->specs, $this->source, $this->dest);

        $MediaDest = \MediaVorus\MediaVorus::guess(new \SplFileInfo($this->dest));

        $this->assertEquals($this->source->getWidth(), $MediaDest->getWidth());
        $this->assertEquals($this->source->getHeight(), $MediaDest->getHeight());
    }

    /**
     * @covers MediaAlchemyst\Transmuter\Image2Image::execute
     */
    public function testExecuteAutorotate()
    {
        Image2Image::$autorotate = true;

        $this->object->execute($this->specs, $this->source, $this->dest);

        $MediaDest = \MediaVorus\MediaVorus::guess(new \SplFileInfo($this->dest));

        $this->assertEquals($this->source->getWidth(), $MediaDest->getHeight());
        $this->assertEquals($this->source->getHeight(), $MediaDest->getWidth());
    }

    /**
     * @covers MediaAlchemyst\Transmuter\Image2Image::execute
     * @covers MediaAlchemyst\Transmuter\Image2Image::extractEmbeddedImage
     */
    public function testExecutePreviewExtract()
    {
        Image2Image::$lookForEmbeddedPreview = true;

        $source = \MediaVorus\MediaVorus::guess(new \SplFileInfo(__DIR__ . '/../../../files/ExifTool.jpg'));

        $this->object->execute($this->specs, $source, $this->dest);

        $MediaDest = \MediaVorus\MediaVorus::guess(new \SplFileInfo($this->dest));

        $this->assertEquals(192, $MediaDest->getHeight());
        $this->assertEquals(288, $MediaDest->getWidth());
    }

    /**
     * @covers MediaAlchemyst\Transmuter\Image2Image::execute
     */
    public function testExecuteSimpleResize()
    {
        $this->specs->setDimensions(320, 240);

        $this->object->execute($this->specs, $this->source, $this->dest);

        $MediaDest = \MediaVorus\MediaVorus::guess(new \SplFileInfo($this->dest));

        $this->assertTrue($MediaDest->getHeight() <= 240);
        $this->assertTrue($MediaDest->getWidth() <= 320);
    }

    /**
     * @covers MediaAlchemyst\Transmuter\Image2Image::execute
     */
    public function testExecuteOutBoundResize()
    {
        $this->specs->setDimensions(240, 260);
        $this->specs->setStrip(true);
        $this->specs->setRotationAngle(-90);
        $this->specs->setResizeMode(\MediaAlchemyst\Specification\Image::RESIZE_MODE_OUTBOUND);

        $this->object->execute($this->specs, $this->source, $this->dest);

        $MediaDest = \MediaVorus\MediaVorus::guess(new \SplFileInfo($this->dest));

        $this->assertEquals(240, $MediaDest->getHeight());
        $this->assertEquals(260, $MediaDest->getWidth());
    }

    /**
     * @covers MediaAlchemyst\Transmuter\Image2Image::execute
     */
    public function testExecuteInSetFixedRatio()
    {
        $this->specs->setDimensions(200, 200);
        $this->specs->setResizeMode(\MediaAlchemyst\Specification\Image::RESIZE_MODE_INBOUND_FIXEDRATIO);

        $this->object->execute($this->specs, $this->source, $this->dest);

        $MediaDest = \MediaVorus\MediaVorus::guess(new \SplFileInfo($this->dest));

        $this->assertTrue(200 >= $MediaDest->getHeight());
        $this->assertTrue(200 >= $MediaDest->getWidth());

        $this->assertEquals(round($this->source->getWidth() / $this->source->getHeight()), round($MediaDest->getWidth() / $MediaDest->getHeight()));
    }

    /**
     * @covers MediaAlchemyst\Transmuter\Image2Image::execute
     * @covers MediaAlchemyst\Exception\SpecNotSupportedException
     * @expectedException \MediaAlchemyst\Exception\SpecNotSupportedException
     */
    public function testWrongSpecs()
    {
        $this->object->execute(new \MediaAlchemyst\Specification\UnknownSpecs(), $this->source, $this->dest);
    }

    /**
     * @covers MediaAlchemyst\Transmuter\Image2Image::execute
     */
    public function testExecuteRawImage()
    {
        $source = \MediaVorus\MediaVorus::guess(new \SplFileInfo(__DIR__ . '/../../../files/RAW_CANON_40D_RAW_V105.cr2'));
        $this->object->execute($this->specs, $source, $this->dest);

        $MediaDest = \MediaVorus\MediaVorus::guess(new \SplFileInfo($this->dest));

        $this->assertEquals(1936, $MediaDest->getWidth());
        $this->assertEquals(1288, $MediaDest->getHeight());
    }
}
