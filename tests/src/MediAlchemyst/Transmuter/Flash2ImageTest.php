<?php

namespace MediAlchemyst\Transmuter;

class Flash2ImageTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @var Flash2Image
     */
    protected $object;
    protected $specs;
    protected $source;
    protected $dest;

    protected function setUp()
    {
        $this->object = new Flash2Image(new \MediAlchemyst\DriversContainer(new \Symfony\Component\DependencyInjection\ParameterBag\ParameterBag(array())));

        $this->specs = new \MediAlchemyst\Specification\Image();
        $this->source = \MediaVorus\MediaVorus::guess(new \SplFileInfo(__DIR__ . '/../../../files/flashfile.swf'));
        $this->dest = __DIR__ . '/../../../files/output.jpg';
    }

    protected function tearDown()
    {
        if (file_exists($this->dest) && is_writable($this->dest))
        {
            unlink($this->dest);
        }
    }

    /**
     * @covers MediAlchemyst\Transmuter\Flash2Image::execute
     * @covers MediAlchemyst\Exception\SpecNotSupportedException
     * @expectedException MediAlchemyst\Exception\SpecNotSupportedException
     */
    public function testExecute()
    {
        $this->specs = new \MediAlchemyst\Specification\Video();
        $this->object->execute($this->specs, $this->source, $this->dest);
    }

    /**
     * @covers MediAlchemyst\Transmuter\Flash2Image::execute
     */
    public function testExecuteWrongSpecs()
    {
        $this->specs->setDimensions(320, 240);

        $this->object->execute($this->specs, $this->source, $this->dest);

        $MediaDest = \MediaVorus\MediaVorus::guess(new \SplFileInfo($this->dest));

        $this->assertEquals(320, $MediaDest->getWidth());
        $this->assertEquals(240, $MediaDest->getHeight());
    }

}
