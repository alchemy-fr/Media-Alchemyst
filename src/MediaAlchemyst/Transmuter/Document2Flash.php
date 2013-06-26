<?php

/*
 * This file is part of Media-Alchemyst.
 *
 * (c) Alchemy <dev.team@alchemy.fr>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace MediaAlchemyst\Transmuter;

use MediaAlchemyst\Specification\Flash;
use MediaAlchemyst\Specification\SpecificationInterface;
use MediaAlchemyst\Exception\SpecNotSupportedException;
use MediaAlchemyst\Exception\RuntimeException;
use MediaVorus\Media\MediaInterface;
use SwfTools\Exception\ExceptionInterface as SwfToolsException;
use Unoconv\Unoconv;
use Unoconv\Exception\ExceptionInterface as UnoconvException;

class Document2Flash extends AbstractTransmuter
{
    public function execute(SpecificationInterface $spec, MediaInterface $source, $dest)
    {
        if (! $spec instanceof Flash) {
            throw new SpecNotSupportedException('SwfTools only accept Flash specs');
        }

        $tmpDest = tempnam(sys_get_temp_dir(), 'pdf2swf');

        try {

            if ($source->getFile()->getMimeType() != 'application/pdf') {
                $this->container['unoconv']->transcode(
                    $source->getFile()->getPathname(), Unoconv::FORMAT_PDF, $tmpDest
                );
            } else {
                copy($source->getFile()->getPathname(), $tmpDest);
            }

            $this->container['swftools.pdf-file']->toSwf($tmpDest, $dest);

            unlink($tmpDest);
        } catch (UnoconvException $e) {
            throw new RuntimeException('Unable to transmute document to flash due to Unoconv', null, $e);
        } catch (SwfToolsException $e) {
            throw new RuntimeException('Unable to transmute document to flash due to SwfTools', null, $e);
        }
    }
}
