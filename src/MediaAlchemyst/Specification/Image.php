<?php

namespace MediaAlchemyst\Specification;

use MediaAlchemyst\Exception;

class Image extends Provider
{

    protected $width;
    protected $height;
    protected $resizeMode = self::RESIZE_MODE_INBOUND;
    protected $rotationAngle;
    protected $strip;

    const RESIZE_MODE_INBOUND  = \Imagine\Image\ImageInterface::THUMBNAIL_INSET;
    const RESIZE_MODE_OUTBOUND = \Imagine\Image\ImageInterface::THUMBNAIL_OUTBOUND;

    public function __construct()
    {

    }

    public function getType()
    {
        return self::TYPE_IMAGE;
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

    public function setResizeMode($mode)
    {
        if ( ! in_array($mode, array(self::RESIZE_MODE_INBOUND, self::RESIZE_MODE_OUTBOUND)))
        {
            throw new Exception\InvalidArgumentException('Invalid resize mode');
        }

        $this->resizeMode = $mode;
    }

    public function getResizeMode()
    {
        return $this->resizeMode;
    }

    public function setRotationAngle($angle)
    {
        $this->rotationAngle = $angle;
    }

    public function getRotationAngle()
    {
        return $this->rotationAngle;
    }

    public function setStrip($boolean)
    {
        $this->strip = $boolean;
    }

    public function getStrip()
    {
        return $this->strip;
    }

}
