<?php

namespace MediAlchemyst\Transmuter;

use MediAlchemyst\Specification;
use MediAlchemyst\Exception;
use MediaVorus\Media\Media;
use Imagine\Image;

class Image2Image extends Provider
{

    public static $autorotate = false;

    public function execute(Specification\Provider $spec, Media $source, $dest)
    {
        if ( ! $spec instanceof Specification\Image)
        {
            throw new Exception\SpecNotSupportedException('Imagine Adapter only supports Image specs');
        }

        $image = $this->container->getImagine()->open($source->getFile()->getPathname());

        if ($spec->getWidth() && $spec->getHeight())
        {
            $box = new Image\Box($spec->getWidth(), $spec->getHeight());

            if ($spec->getResizeMode() == Specification\Image::RESIZE_MODE_OUTBOUND)
            {
                /* @var $image \Imagine\Gmagick\Image */
                $image = $image->thumbnail($box, Image\ImageInterface::THUMBNAIL_OUTBOUND);
            }
            else
            {
                $image = $image->resize($box);
            }
        }

        if (static::$autorotate)
        {
            $image = $image->rotate(- $source->getOrientation());
        }
        elseif (null !== $angle = $spec->getRotationAngle())
        {
            $image = $image->rotate($angle);
        }

        if (true == $spec->getStrip())
        {
            $image = $image->strip();
        }

        $image->save($dest);
    }

}
