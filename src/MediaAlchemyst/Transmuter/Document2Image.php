<?php

namespace MediaAlchemyst\Transmuter;

use MediaAlchemyst\Specification;
use MediaAlchemyst\Exception;
use MediaAlchemyst\Exception\RuntimeException;
use MediaVorus\Media\Media;

class Document2Image extends Provider
{

    public function execute(Specification\Provider $spec, Media $source, $dest)
    {
        if ( ! $spec instanceof Specification\Image) {
            throw new Exception\SpecNotSupportedException('SwfTools only accept Image specs');
        }

        $toremove = array();
        $toremove[] = $tmpDest = tempnam(sys_get_temp_dir(), 'unoconv');

        try {

            /**
             * Support for unoconv < 0.4 : pagerange is not supported
             */
            if ($source->getFile()->getMimeType() != 'application/pdf') {
                $this->container->getUnoconv()
                    ->open($source->getFile()->getPathname())
                    ->saveAs(\Unoconv\Unoconv::FORMAT_PDF, $tmpDest)
//                    ->saveAs(\Unoconv\Unoconv::FORMAT_PDF, $tmpDest, '1-1')
                    ->close();
            } else {
                copy($source->getFile()->getPathname(), $tmpDest);
            }

            $image = $this->container->getImagine()->open($tmpDest);

            $options = array(
                'quality'          => $spec->getQuality(),
                'resolution-units' => $spec->getResolutionUnit(),
                'resolution-x'     => $spec->getResolutionX(),
                'resolution-y'     => $spec->getResolutionY(),
            );

            $image->save($dest, $options);

            if ($spec->getWidth() && $spec->getHeight()) {

                $toremove[] = $tmpDest = tempnam(sys_get_temp_dir(), 'unoconv');
                rename($dest, $tmpDest);

                $image = $this->container->getImagine()->open($tmpDest);

                $media = $this->container['mediavorus']->guess(new \SplFileInfo($tmpDest));

                $box = $this->boxFromImageSpec($spec, $media);

                if ($spec->getResizeMode() == Specification\Image::RESIZE_MODE_OUTBOUND) {
                    /* @var $image \Imagine\Gmagick\Image */
                    $image = $image->thumbnail($box, Image\ImageInterface::THUMBNAIL_OUTBOUND);
                } else {
                    $image = $image->resize($box);
                }

                $image->save($dest, $options);

                unset($media);
            }

            foreach ($toremove as $tmpDest) {
                unlink($tmpDest);
            }
        } catch (\Unoconv\Exception\Exception $e) {
            throw new RuntimeException($e->getMessage(), $e->getCode(), $e);
        } catch (\Imagine\Exception\Exception $e) {
            throw new RuntimeException($e->getMessage(), $e->getCode(), $e);
        } catch (\MediaVorus\Exception\Exception $e) {
            throw new RuntimeException($e->getMessage(), $e->getCode(), $e);
        }
    }
}
