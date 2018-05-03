<?php

namespace MediaAlchemyst\Tests\Specification;

use MediaAlchemyst\Specification\Flash;
use MediaAlchemyst\Specification\SpecificationInterface;
use \PHPUnit\Framework\TestCase;

class FlashTest extends TestCase
{
    /**
     * @covers MediaAlchemyst\Specification\Flash::getType
     */
    public function testGetType()
    {
        $specs = new Flash;
        $this->assertEquals(SpecificationInterface::TYPE_SWF, $specs->getType());
    }
}
