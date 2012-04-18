<?php

namespace MediAlchemyst\Transmuter;

use MediAlchemyst\Specification;
use MediAlchemyst\Exception;
use MediaVorus\Media\Media;

class Flash2Image extends Provider
{

    public function execute(Specification\Provider $spec, Media $source, $dest)
    {
        if ( ! $spec instanceof Specification\Image)
        {
            throw new Exception\SpecNotSupportedException('SwfTools only accept Image specs');
        }

        $tmpDest = tempnam(sys_get_temp_dir(), 'swfrender');

        $this->container->getSwfRender()
          ->render($source->getFile(), $tmpDest, null);

        $image = $this->container->getImagine()->open($tmpDest);

        if ($spec->getWidth() && $spec->getHeight())
        {
            $box   = new \Imagine\Image\Box($spec->getWidth(), $spec->getHeight());
            $image = $image->resize($box);
        }

        $image->save($dest);

        unlink($tmpDest);
    }

}
