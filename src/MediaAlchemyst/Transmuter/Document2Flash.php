<?php

namespace MediaAlchemyst\Transmuter;

use MediaAlchemyst\Specification;
use MediaAlchemyst\Exception;
use MediaVorus\Media\MediaInterface;

class Document2Flash extends Provider
{

    public function execute(Specification\Provider $spec, MediaInterface $source, $dest)
    {
        if ( ! $spec instanceof Specification\Flash) {
            throw new Exception\SpecNotSupportedException('SwfTools only accept Flash specs');
        }

        $tmpDest = tempnam(sys_get_temp_dir(), 'pdf2swf');

        try {

            if ($source->getFile()->getMimeType() != 'application/pdf') {
                $this->container['unoconv']
                    ->open($source->getFile()->getPathname())
                    ->saveAs(\Unoconv\Unoconv::FORMAT_PDF, $tmpDest)
                    ->close();
            } else {
                copy($source->getFile()->getPathname(), $tmpDest);
            }

            $this->container['xpdf.pdf2swf']
                ->toSwf(new \SplFileInfo($tmpDest), $dest);

            unlink($tmpDest);
        } catch (\Unoconv\Exception\Exception $e) {
            throw new Exception\RuntimeException($e->getMessage(), $e->getCode(), $e);
        } catch (\SwfTools\Exception\Exception $e) {
            throw new Exception\RuntimeException($e->getMessage(), $e->getCode(), $e);
        }
    }
}
