<?php

namespace MediaAlchemyst\Transmuter;

use MediaAlchemyst\Specification;
use MediaAlchemyst\Exception;
use MediaVorus\Media\Media;

class Document2Image extends Provider
{

    public function execute(Specification\Provider $spec, Media $source, $dest)
    {
        if ( ! $spec instanceof Specification\Image) {
            throw new Exception\SpecNotSupportedException('SwfTools only accept Image specs');
        }

        $tmpDest = tempnam(sys_get_temp_dir(), 'unoconv');

        try {
            $this->container->getUnoconv()
              ->open($source->getFile()->getPathname())
              ->saveAs(\Unoconv\Unoconv::FORMAT_PDF, $tmpDest, '1-1')
              ->close();

            $image = $this->container->getImagine()->open($tmpDest);

            if ($spec->getWidth() && $spec->getHeight()) {

                $box = $this->boxFromImageSpec($spec, $source);

                if ($spec->getResizeMode() == Specification\Image::RESIZE_MODE_OUTBOUND) {
                    /* @var $image \Imagine\Gmagick\Image */
                    $image = $image->thumbnail($box, Image\ImageInterface::THUMBNAIL_OUTBOUND);
                } else {
                    $image = $image->resize($box);
                }
            }

            $options = array(
              'quality'          => $spec->getQuality(),
              'resolution-units' => $spec->getResolutionUnit(),
              'resolution-x'     => $spec->getResolutionX(),
              'resolution-y'     => $spec->getResolutionY(),
            );

            $image->save($dest, $options);

            unlink($tmpDest);
        } catch (\Unoconv\Exception\Exception $e) {
            throw new Exception\RuntimeException($e->getMessage(), $e->getCode(), $e);
        } catch (\Imagine\Exception\Exception $e) {
            throw new Exception\RuntimeException($e->getMessage(), $e->getCode(), $e);
        }
    }

}
