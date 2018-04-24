<?php

namespace MediaAlchemyst\Tests\Specification;

use MediaAlchemyst\Specification\Animation;
use MediaAlchemyst\Specification\SpecificationInterface;
use \PHPUnit\Framework\TestCase;

class AnimationTest extends TestCase
{
    /**
     * @var Animation
     */
    protected $object;

    protected function setUp()
    {
        $this->object = new Animation();
    }

    public function testGetType()
    {
        $this->assertEquals(SpecificationInterface::TYPE_ANIMATION, $this->object->getType());
    }

    /**
     * @covers MediaAlchemyst\Specification\Animation::setDelay
     * @covers MediaAlchemyst\Specification\Animation::getDelay
     */
    public function testSetDelay()
    {
        $this->object->setDelay(800);
        $this->assertEquals(800, $this->object->getDelay());
    }
}
