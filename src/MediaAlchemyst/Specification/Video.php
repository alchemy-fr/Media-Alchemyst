<?php

namespace MediaAlchemyst\Specification;

use FFMpeg\Format\Video\DefaultVideo;
use MediaAlchemyst\Exception\InvalidArgumentException;

class Video extends Audio
{
    protected $width;
    protected $height;
    protected $videoCodec;
    protected $resizeMode = self::RESIZE_MODE_INSET;
    protected $GOPSize;
    protected $framerate;

    const RESIZE_MODE_FIT = DefaultVideo::RESIZEMODE_FIT;
    const RESIZE_MODE_INSET = DefaultVideo::RESIZEMODE_INSET;

    public function getType()
    {
        return self::TYPE_VIDEO;
    }

    public function setResizeMode($mode)
    {
        if ( ! in_array($mode, array(self::RESIZE_MODE_INSET, self::RESIZE_MODE_FIT))) {
            throw new InvalidArgumentException('Invalid resize mode');
        }

        $this->resizeMode = $mode;
    }

    public function getResizeMode()
    {
        return $this->resizeMode;
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
