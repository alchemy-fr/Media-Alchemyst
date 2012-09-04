<?php

namespace MediaAlchemyst\Transmuter;

use Imagine\Exception\Exception as ImagineException;
use Imagine\Image\ImageInterface;
use MediaVorus\Exception\ExceptionInterface as MediaVorusException;
use MediaAlchemyst\Specification\Image;
use MediaAlchemyst\Specification\SpecificationInterface;
use MediaAlchemyst\Exception\SpecNotSupportedException;
use MediaVorus\Media\MediaInterface;
use SwfTools\Exception\ExceptionInterface as SwfToolsException;

class Flash2Image extends AbstractTransmuter
{

    public function execute(SpecificationInterface $spec, MediaInterface $source, $dest)
    {
        if ( ! $spec instanceof Image) {
            throw new SpecNotSupportedException('SwfTools only accept Image specs');
        }

        $tmpDest = tempnam(sys_get_temp_dir(), 'swfrender');

        try {
            $tmpDest = $this->container['swftools.flash-file']
                ->open($source->getFile()->getPathname())
                ->render($tmpDest);
            $this->container['swftools.flash-file']->close();

            $image = $this->container['imagine']->open($tmpDest);

            if ($spec->getWidth() && $spec->getHeight()) {

                $media = $this->container['mediavorus']->guess($tmpDest);

                $box = $this->boxFromImageSpec($spec, $media);

                if ($spec->getResizeMode() == Image::RESIZE_MODE_OUTBOUND) {
                    /* @var $image \Imagine\Gmagick\Image */
                    $image = $image->thumbnail($box, ImageInterface::THUMBNAIL_OUTBOUND);
                } else {
                    $image = $image->resize($box);
                }

                unset($media);
            }

            $options = array(
                'quality'          => $spec->getQuality(),
                'resolution-units' => $spec->getResolutionUnit(),
                'resolution-x'     => $spec->getResolutionX(),
                'resolution-y'     => $spec->getResolutionY(),
            );

            $image->save($dest, $options);

            unlink($tmpDest);
        } catch (SwfToolsException $e) {
            throw new RuntimeException('Unable to transmute flash to image due to SwfTools', null, $e);
        } catch (ImagineException $e) {
            throw new RuntimeException('Unable to transmute flash to image due to Imagine', null, $e);
        } catch (MediaVorusException $e) {
            throw new RuntimeException('Unable to transmute flash to image due to MediaVorus', null, $e);
        }
    }
}
