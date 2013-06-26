<?php

namespace MediaAlchemyst\Specification;

class Audio extends AbstractSpecification
{

    protected $audioKiloBitrate;
    protected $audioCodec;
    protected $audioSampleRate;
    protected $fileType;

    public function getType()
    {
        return self::TYPE_AUDIO;
    }

    public function setAudioKiloBitrate($kiloBitrate)
    {
        $this->audioKiloBitrate = $kiloBitrate;
    }

    public function getAudioKiloBitrate()
    {
        return $this->audioKiloBitrate;
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
