<?php

namespace MediaAlchemyst\Transmuter;

use MediaAlchemyst\AbstractAlchemystTester;

require_once __DIR__ . '/../AbstractAlchemystTester.php';

class Document2ImageTest extends AbstractAlchemystTester
{

    /**
     * @var Document2Image
     */
    protected $object;
    protected $specs;
    protected $source;
    protected $dest;

    protected function setUp()
    {
        $executableFinder = new \Symfony\Component\Process\ExecutableFinder();
        if ( ! $executableFinder->find('unoconv')) {
            $this->markTestSkipped('Unoconv is not installed');
        }

        $this->object = new Document2Image(new \MediaAlchemyst\DriversContainer(new \Symfony\Component\DependencyInjection\ParameterBag\ParameterBag(array())));

        $this->specs = new \MediaAlchemyst\Specification\Image();
        $this->source = $this->getMediaVorus()->guess(__DIR__ . '/../../../files/Hello.odt');
        $this->dest = __DIR__ . '/../../../files/output.jpg';
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown()
    {
        if (file_exists($this->dest) && is_writable($this->dest)) {
            unlink($this->dest);
        }
    }

    /**
     * @covers MediaAlchemyst\Transmuter\Document2Image::execute
     */
    public function testExecute()
    {
        $this->specs->setDimensions(320, 240);
        $this->specs->setResizeMode(\MediaAlchemyst\Specification\Image::RESIZE_MODE_INBOUND);

        $this->object->execute($this->specs, $this->source, $this->dest);

        $MediaDest = $this->getMediaVorus()->guess($this->dest);

        $this->assertEquals(320, $MediaDest->getWidth());
        $this->assertEquals(240, $MediaDest->getHeight());
    }

    /**
     * @covers MediaAlchemyst\Transmuter\Document2Image::execute
     * @covers MediaAlchemyst\Exception\SpecNotSupportedException
     * @expectedException MediaAlchemyst\Exception\SpecNotSupportedException
     */
    public function testExecuteWrongSpecs()
    {
        $this->specs = new \MediaAlchemyst\Specification\Video();
        $this->object->execute($this->specs, $this->source, $this->dest);
    }

}
