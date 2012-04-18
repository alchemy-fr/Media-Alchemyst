<?php

namespace MediaAlchemyst\Specification;

use MediaAlchemyst\Exception;

class Flash extends Provider
{

    public function getType()
    {
        return self::TYPE_SWF;
    }

}
