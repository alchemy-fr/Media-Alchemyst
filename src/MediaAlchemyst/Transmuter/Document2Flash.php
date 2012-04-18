<?php

namespace MediaAlchemyst\Transmuter;

use MediaAlchemyst\Specification;
use MediaAlchemyst\Exception;
use MediaVorus\Media\Media;

class Document2Flash extends Provider
{

    public function execute(Specification\Provider $spec, Media $source, $dest)
    {
        if ( ! $spec instanceof Specification\Flash)
        {
            throw new Exception\SpecNotSupportedException('SwfTools only accept Flash specs');
        }

        $tmpDest = tempnam(sys_get_temp_dir(), 'pdf2swf');

        /**
         * unoconv =>pdf
         * imagine => image
         */
        $this->container->getUnoconv()
          ->open($source->getFile()->getPathname())
          ->saveAs(\Unoconv\Unoconv::TYPE_PDF, $tmpDest)
          ->close();

        $this->container->getPdf2Swf()
          ->toSwf(new \SplFileInfo($tmpDest), $dest );

        unlink($tmpDest);
    }

}
