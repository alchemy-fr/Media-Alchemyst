<?php

namespace MediaAlchemyst\Transmuter;

class Video2AnimationTest extends \PHPUnit_Framework_TestCase
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
    protected $mediavorus;

    protected function setUp()
    {
        $this->mediavorus = new \MediaVorus\MediaVorus();
        $this->object = new Video2Animation(new \MediaAlchemyst\DriversContainer(new \Symfony\Component\DependencyInjection\ParameterBag\ParameterBag(array())));

        $this->specs = new Animation();
        $this->specs->setDimensions(130, 110);
        $this->specs->setResizeMode(Animation::RESIZE_MODE_OUTBOUND);

        $this->source = $this->getMediaVorus()->guess(new \SplFileInfo(__DIR__ . '/../../../files/Test.ogv'));
        $this->dest = __DIR__ . '/../../../files/output_.gif';
    }

    /**
     * @covers MediaAlchemyst\Transmuter\Video2Animation::execute
     * @todo Implement testExecute().
     */
    public function testExecute()
    {
        $this->object->execute($this->specs, $this->source, $this->dest);

        $output = $this->getMediaVorus()->guess($this->dest);

        $this->assertEquals(130, $output->getWidth());
        $this->assertEquals(110, $output->getHeight());
    }

}

?>
