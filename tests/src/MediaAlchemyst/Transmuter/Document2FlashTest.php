<?php

namespace MediaAlchemyst\Transmuter;

use MediaAlchemyst\AbstractAlchemystTester;

require_once __DIR__ . '/../AbstractAlchemystTester.php';

class Document2FlashTest extends AbstractAlchemystTester
{

    /**
     * @var Document2Flash
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

        $this->object = new Document2Flash(new \MediaAlchemyst\DriversContainer(new \Symfony\Component\DependencyInjection\ParameterBag\ParameterBag(array())));

        $this->specs = new \MediaAlchemyst\Specification\Flash();

        $this->source = $this->getMediaVorus()->guess(__DIR__ . '/../../../files/Hello.odt');
        $this->dest = __DIR__ . '/../../../files/output.swf';
    }

    protected function tearDown()
    {
        if (file_exists($this->dest) && is_writable($this->dest)) {
            unlink($this->dest);
        }
    }

    /**
     * @covers MediaAlchemyst\Transmuter\Document2Flash::execute
     */
    public function testExecute()
    {
        $this->object->execute($this->specs, $this->source, $this->dest);

        $MediaDest = $this->getMediaVorus()->guess($this->dest);

        $this->assertEquals(\MediaVorus\Media\MediaInterface::TYPE_FLASH, $MediaDest->getType());
    }

    /**
     * @covers MediaAlchemyst\Transmuter\Document2Flash::execute
     * @expectedException MediaAlchemyst\Exception\SpecNotSupportedException
     */
    public function testExecuteWrongSpecs()
    {
        $this->specs = new \MediaAlchemyst\Specification\Video();
        $this->object->execute($this->specs, $this->source, $this->dest);
    }

}
