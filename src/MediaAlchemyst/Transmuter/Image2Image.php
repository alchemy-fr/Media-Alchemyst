<?php

namespace MediaAlchemyst\Transmuter;

use MediaAlchemyst\Specification;
use MediaAlchemyst\Exception;
use MediaVorus\Media\Media;
use Imagine\Image;

class Image2Image extends Provider
{

    public static $autorotate             = false;
    public static $lookForEmbeddedPreview = false;

    public function execute(Specification\Provider $spec, Media $source, $dest)
    {
        if ( ! $spec instanceof Specification\Image)
        {
            throw new Exception\SpecNotSupportedException('Imagine Adapter only supports Image specs');
        }

        if ($source->getType() !== Media::TYPE_IMAGE)
        {
            throw new Exception\SpecNotSupportedException('Imagine Adapter only supports Images');
        }

        $to_remove = null;

        if (static::$lookForEmbeddedPreview)
        {
            $tmpFile = $this->extractEmbeddedImage($source->getFile()->getPathname());

            if ($tmpFile instanceof Media)
            {
                $source    = $tmpFile;
                $to_remove = $tmpFile->getFile();
            }
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

        $image->save($dest, array('quality' => $spec->getQuality()));

        if ($to_remove)
        {
            unlink($to_remove);
            rmdir(dirname($to_remove));
        }
    }

    protected function extractEmbeddedImage($pathfile)
    {
        $tmpDir = sys_get_temp_dir() . '/extractor' . mt_rand(1000000, 9999999);

        mkdir($tmpDir);

        $files = $this->container->getExiftoolExtractor()->extract($pathfile, $tmpDir);

        $to_unlink = array();
        $selected = null;
        $size = null;

        foreach ($files as $file)
        {
            if ($file->isDir() || $file->isDot())
            {
                continue;
            }

            if (is_null($selected) || $file->getSize() > $size)
            {
                $selected = $file->getPathname();
                $size = $file->getSize();
            }
            else
            {
                array_push($to_unlink, $file->getPathname());
            }
        }

        foreach ($to_unlink as $pathname)
        {
            if ($pathname != $selected)
            {
                unlink($pathname);
            }
        }

        if ($selected)
        {
            return \MediaVorus\MediaVorus::guess(new \SplFileInfo($selected));
        }

        rmdir($tmpDir);

        return null;
    }

}
