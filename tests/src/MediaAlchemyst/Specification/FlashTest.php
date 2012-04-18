<?php

namespace MediaAlchemyst\Specification;

class FlashTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @covers MediaAlchemyst\Specification\Flash::getType
     */
    public function testGetType()
    {
        $specs = new Flash;
        $this->assertEquals(Specification::TYPE_SWF, $specs->getType());
    }

}
