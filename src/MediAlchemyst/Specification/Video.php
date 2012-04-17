<?php

namespace MediAlchemyst\Specification;

use MediAlchemyst\Exception;

class Video extends Audio
{

    protected $width;
    protected $height;
    protected $videoCodec;

    const FILETYPE_X264 = 'x264';
    const FILETYPE_WEBM = 'WebM';
    const FILETYPE_OGG  = 'Ogg';

    public function getType()
    {
        return self::TYPE_VIDEO;
    }

    public function setFileType($fileType)
    {
        if ( ! in_array($fileType, array(self::FILETYPE_X264, self::FILETYPE_WEBM, self::FILETYPE_OGG)))
        {
            throw new Exception\InvalidArgumentException(sprintf('Invalid format %s', $fileType));
        }

        $this->fileType = $fileType;
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
