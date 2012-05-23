<?php

namespace MediaAlchemyst\Specification;


class Video extends Audio
{

    protected $width;
    protected $height;
    protected $videoCodec;
    protected $GOPSize;
    protected $framerate;

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

    public function getGOPSize()
    {
        return $this->GOPSize;
    }

    public function setGOPSize($GOPSize)
    {
        $this->GOPSize = $GOPSize;
    }

    public function getFramerate()
    {
        return $this->framerate;
    }

    public function setFramerate($framerate)
    {
        $this->framerate = $framerate;
    }

}
