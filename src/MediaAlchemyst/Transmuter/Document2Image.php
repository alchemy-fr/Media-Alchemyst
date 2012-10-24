<?php

namespace MediaAlchemyst\Transmuter;

use Ghostscript\Exception\ExceptionInterface as GhostscriptException;
use Imagine\Exception\Exception as ImagineException;
use Imagine\Image\ImageInterface;
use MediaVorus\Exception\ExceptionInterface as MediaVorusException;
use MediaAlchemyst\Specification\Image;
use MediaAlchemyst\Specification\SpecificationInterface;
use MediaAlchemyst\Exception\SpecNotSupportedException;
use MediaAlchemyst\Exception\RuntimeException;
use MediaVorus\Media\MediaInterface;
use Unoconv\Unoconv;
use Unoconv\Exception\ExceptionInterface as UnoconvException;

class Document2Image extends AbstractTransmuter
{

    public function execute(SpecificationInterface $spec, MediaInterface $source, $dest)
    {
        if ( ! $spec instanceof Image) {
            throw new SpecNotSupportedException('SwfTools only accept Image specs');
        }

        $toremove = array();
        $toremove[] = $tmpDest = tempnam(sys_get_temp_dir(), 'unoconv');

        try {

            if ($source->getFile()->getMimeType() != 'application/pdf') {
                $this->container['unoconv']
                    ->open($source->getFile()->getPathname())
                    ->saveAs(Unoconv::FORMAT_PDF, $tmpDest, '1-1')
                    ->close();
            } else {
                copy($source->getFile()->getPathname(), $tmpDest);
            }

            $toremove[] = $tmpDestSinglePage = tempnam(sys_get_temp_dir(), 'unoconv-single');

            $this->container['ghostscript.pdf-transcoder']->open($tmpDest)
                ->transcode($tmpDestSinglePage, 1, 1)
                ->close();

            $image = $this->container['imagine']->open($tmpDestSinglePage);

            $options = array(
                'quality'          => $spec->getQuality(),
                'resolution-units' => $spec->getResolutionUnit(),
                'resolution-x'     => $spec->getResolutionX(),
                'resolution-y'     => $spec->getResolutionY(),
            );

            $image->save($dest, $options);

            if ($spec->getWidth() && $spec->getHeight()) {

                $toremove[] = $tmpImage = tempnam(sys_get_temp_dir(), 'unoconv');
                rename($dest, $tmpImage);

                $image = $this->container['imagine']->open($tmpImage);

                $media = $this->container['mediavorus']->guess($tmpImage);

                $box = $this->boxFromImageSpec($spec, $media);

                if ($spec->getResizeMode() == Image::RESIZE_MODE_OUTBOUND) {
                    /* @var $image \Imagine\Gmagick\Image */
                    $image = $image->thumbnail($box, ImageInterface::THUMBNAIL_OUTBOUND);
                } else {
                    $image = $image->resize($box);
                }

                $image->save($dest, $options);

                unset($media);
            }

            foreach ($toremove as $tmpDest) {
                unlink($tmpDest);
            }
        } catch (GhostscriptException $e) {
            throw new RuntimeException($e->getMessage(), $e->getCode(), $e);
        } catch (UnoconvException $e) {
            throw new RuntimeException('Unable to transmute document to image due to Unoconv', null, $e);
        } catch (ImagineException $e) {
            throw new RuntimeException('Unable to transmute document to image due to Imagine', null, $e);
        } catch (MediaVorusException $e) {
            throw new RuntimeException('Unable to transmute document to image due to MediaVorus', null, $e);
        }
    }
}
