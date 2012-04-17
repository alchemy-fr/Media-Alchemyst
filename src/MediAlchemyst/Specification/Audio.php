<?php

namespace MediAlchemyst\Specification;

use MediAlchemyst\Exception;

class Audio extends Provider
{

    protected $kiloBitrate;
    protected $audioCodec;
    protected $audioSampleRate;
    protected $fileType;

    const FILETYPE_FLAC = 'flac';
    const FILETYPE_MP3  = 'mp3';

    public function __construct()
    {

    }

    public function getType()
    {
        return self::TYPE_AUDIO;
    }

    public function getFileType()
    {
        return $this->fileType;
    }

    public function setFileType($fileType)
    {
        if ( ! in_array($fileType, array(self::FILETYPE_FLAC, self::FILETYPE_MP3)))
        {
            throw new Exception\InvalidArgumentException(sprintf('Invalid format %s', $fileType));
        }
        
        $this->fileType = $fileType;
    }

    public function setKiloBitrate($kiloBitrate)
    {
        $this->kiloBitrate = $kiloBitrate;
    }

    public function getKiloBitrate()
    {
        return $this->kiloBitrate;
    }

    public function setAudioCodec($audioCodec)
    {
        $this->audioCodec = $audioCodec;
    }

    public function getAudioCodec()
    {
        return $this->audioCodec;
    }

    public function setAudioSampleRate($audioSampleRate)
    {
        $this->audioSampleRate = $audioSampleRate;
    }

    public function getAudioSampleRate()
    {
        return $this->audioSampleRate;
    }

}
