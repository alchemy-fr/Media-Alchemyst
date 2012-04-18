<?php

namespace MediaAlchemyst\Specification;

use MediaAlchemyst\Exception;

class Video extends Audio
{

    protected $width;
    protected $height;
    protected $videoCodec;

    public function getType()
    {
        return self::TYPE_VIDEO;
    }

    public function setVideoCodec($audioCodec)
    {
        $this->videoCodec = $audioCodec;
    }

    public function getVideoCodec()
    {
        return $this->videoCodec;
    }

    public function setDimensions($width, $height)
    {
        $this->width = $width;
        $this->height = $height;
    }

    public function getWidth()
    {
        return $this->width;
    }

    public function getHeight()
    {
        return $this->height;
    }

}
