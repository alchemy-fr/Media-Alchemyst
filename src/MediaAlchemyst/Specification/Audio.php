<?php

namespace MediaAlchemyst\Specification;


class Audio extends AbstractSpecification
{

    protected $kiloBitrate;
    protected $audioCodec;
    protected $audioSampleRate;
    protected $fileType;

    public function __construct()
    {

    }

    public function getType()
    {
        return self::TYPE_AUDIO;
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
