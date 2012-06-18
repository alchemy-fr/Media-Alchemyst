<?php

namespace MediaAlchemyst\Transmuter;

class Flash2ImageTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @var Flash2Image
     */
    protected $object;
    protected $specs;
    protected $source;
    protected $dest;
    protected $mediavorus;

    protected function setUp()
    {
        $this->mediavorus = new \MediaVorus\MediaVorus();
        $this->object = new Flash2Image(new \MediaAlchemyst\DriversContainer(new \Symfony\Component\DependencyInjection\ParameterBag\ParameterBag(array())));

        $this->specs = new \MediaAlchemyst\Specification\Image();
        $this->source = $this->mediavorus->guess(new \SplFileInfo(__DIR__ . '/../../../files/flashfile.swf'));
        $this->dest = __DIR__ . '/../../../files/output.jpg';
    }

    protected function tearDown()
    {
        if (file_exists($this->dest) && is_writable($this->dest)) {
            unlink($this->dest);
        }
    }

    /**
     * @covers MediaAlchemyst\Transmuter\Flash2Image::execute
     * @covers MediaAlchemyst\Exception\SpecNotSupportedException
     * @expectedException MediaAlchemyst\Exception\SpecNotSupportedException
     */
    public function testExecuteWrongSpecs()
    {
        $this->specs = new \MediaAlchemyst\Specification\Video();
        $this->object->execute($this->specs, $this->source, $this->dest);
    }

    /**
     * @covers MediaAlchemyst\Transmuter\Flash2Image::execute
     */
    public function testExecute()
    {
        $this->specs->setDimensions(320, 240);
        $this->specs->setResizeMode(\MediaAlchemyst\Specification\Image::RESIZE_MODE_INBOUND);

        $this->object->execute($this->specs, $this->source, $this->dest);

        $MediaDest = $this->mediavorus->guess(new \SplFileInfo($this->dest));

        $this->assertEquals(320, $MediaDest->getWidth());
        $this->assertEquals(240, $MediaDest->getHeight());

        $this->specs->setResizeMode(\MediaAlchemyst\Specification\Image::RESIZE_MODE_INBOUND_FIXEDRATIO);

        $this->object->execute($this->specs, $this->source, $this->dest);

        $MediaDest = $this->mediavorus->guess(new \SplFileInfo($this->dest));

        $this->assertEquals(320, $MediaDest->getWidth());
        $this->assertEquals(148, $MediaDest->getHeight());
    }

}
