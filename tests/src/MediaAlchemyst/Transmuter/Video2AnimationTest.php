<?php

namespace MediaAlchemyst\Transmuter;

use MediaAlchemyst\AbstractAlchemystTester;

require_once __DIR__ . '/../AbstractAlchemystTester.php';

class Video2AnimationTest extends AbstractAlchemystTester
{

    /**
     * @var Video2Animation
     */
    protected $object;

    /**
     *
     * @var \MediaAlchemyst\Specification\Animation
     */
    protected $specs;
    protected $source;
    protected $dest;

    protected function setUp()
    {
        $this->object = new Video2Animation(new \MediaAlchemyst\DriversContainer(new \Symfony\Component\DependencyInjection\ParameterBag\ParameterBag(array())));

        $this->specs = new \MediaAlchemyst\Specification\Animation();
        $this->source = $this->getMediaVorus()->guess(__DIR__ . '/../../../files/Test.ogv');
        $this->dest = __DIR__ . '/../../../files/output_.gif';
    }

    /**
     * @covers MediaAlchemyst\Transmuter\Video2Animation::execute
     * @todo Implement testExecute().
     */
    public function testExecute()
    {
        $this->object->execute($this->specs, $this->source, $this->dest);
    }

}

?>
