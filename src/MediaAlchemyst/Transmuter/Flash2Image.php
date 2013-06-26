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

use Imagine\Exception\Exception as ImagineException;
use Imagine\Image\ImageInterface;
use MediaVorus\Exception\ExceptionInterface as MediaVorusException;
use Alchemy\BinaryDriver\Exception\ExceptionInterface as BinaryAdapterException;
use MediaAlchemyst\Specification\Image;
use MediaAlchemyst\Specification\SpecificationInterface;
use MediaAlchemyst\Exception\RuntimeException;
use MediaAlchemyst\Exception\SpecNotSupportedException;
use MediaVorus\Media\MediaInterface;
use SwfTools\Exception\ExceptionInterface as SwfToolsException;

class Flash2Image extends AbstractTransmuter
{
    public function execute(SpecificationInterface $spec, MediaInterface $source, $dest)
    {
        if (! $spec instanceof Image) {
            throw new SpecNotSupportedException('SwfTools only accept Image specs');
        }

        $tmpDest = tempnam(sys_get_temp_dir(), 'swfrender');

        try {
            $tmpDest = $this->container['swftools.flash-file']->render(
                $source->getFile()->getPathname(), $tmpDest
            );

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
        } catch (BinaryAdapterException $e) {
            throw new RuntimeException('Unable to transmute flash to image due to Binary Adappter', $e->getCode(), $e);
        } catch (SwfToolsException $e) {
            throw new RuntimeException('Unable to transmute flash to image due to SwfTools', $e->getCode(), $e);
        } catch (ImagineException $e) {
            throw new RuntimeException('Unable to transmute flash to image due to Imagine', $e->getCode(), $e);
        } catch (MediaVorusException $e) {
            throw new RuntimeException('Unable to transmute flash to image due to MediaVorus', $e->getCode(), $e);
        }
    }
}
