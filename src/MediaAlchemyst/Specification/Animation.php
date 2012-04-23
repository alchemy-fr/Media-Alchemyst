<?php

namespace MediaAlchemyst\Specification;

class Animation extends Image
{

    protected $delay;

    public function getType()
    {
        return self::TYPE_ANIMATION;
    }

    public function setDelay($delay)
    {
        $this->delay = $delay;
    }

    public function getDelay()
    {
        return $this->delay;
    }

}
