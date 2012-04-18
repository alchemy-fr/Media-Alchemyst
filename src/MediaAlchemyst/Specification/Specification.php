<?php

namespace MediaAlchemyst\Specification;

interface Specification
{
    const TYPE_IMAGE = 'image';
    const TYPE_VIDEO = 'video';
    const TYPE_AUDIO = 'audio';
    const TYPE_SWF = 'swf';

    public function getType();
}