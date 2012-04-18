<?php

namespace MediaAlchemyst\Transmuter;

use MediaAlchemyst\Specification;
use MediaAlchemyst\Exception;
use MediaVorus\Media\Media;

class Document2Image extends Provider
{

    public function execute(Specification\Provider $spec, Media $source, $dest)
    {
        if ( ! $spec instanceof Specification\Image)
        {
            throw new Exception\SpecNotSupportedException('SwfTools only accept Image specs');
        }

        $tmpDest = tempnam(sys_get_temp_dir(), 'unoconv');

        /**
         * unoconv =>pdf
         * imagine => image
         */
        $this->container->getUnoconv()
          ->open($source->getFile()->getPathname())
          ->saveAs(\Unoconv\Unoconv::TYPE_PDF, $tmpDest, '1-1')
          ->close();

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
