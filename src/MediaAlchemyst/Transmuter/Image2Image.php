<?php

namespace MediaAlchemyst\Transmuter;

use MediaAlchemyst\Specification;
use MediaAlchemyst\Exception;
use MediaVorus\Media\MediaInterface;
use Imagine\Image;

class Image2Image extends Provider
{
    public static $autorotate = false;
    public static $lookForEmbeddedPreview = false;

    public function execute(Specification\Provider $spec, MediaInterface $source, $dest)
    {
        if ( ! $spec instanceof Specification\Image) {
            throw new Exception\SpecNotSupportedException('Imagine Adapter only supports Image specs');
        }

        if ($source->getType() !== MediaInterface::TYPE_IMAGE) {
            throw new Exception\SpecNotSupportedException('Imagine Adapter only supports Images');
        }

        try {
            $to_remove = null;

            if (static::$lookForEmbeddedPreview) {
                $tmpFile = $this->extractEmbeddedImage($source->getFile()->getPathname());

                if ($tmpFile instanceof Media) {
                    $source = $tmpFile;
                    $to_remove = $tmpFile->getFile();
                }
            }

            $image = $this->container['imagine']->open($source->getFile()->getPathname());

            if ($spec->getWidth() && $spec->getHeight()) {

                $box = $this->boxFromImageSpec($spec, $source);

                if ($spec->getResizeMode() == Specification\Image::RESIZE_MODE_OUTBOUND) {
                    /* @var $image \Imagine\Gmagick\Image */
                    $image = $image->thumbnail($box, Image\ImageInterface::THUMBNAIL_OUTBOUND);
                } else {
                    $image = $image->resize($box);
                }
            }

            if (static::$autorotate) {
                $image = $image->rotate(- $source->getOrientation());
            } elseif (null !== $angle = $spec->getRotationAngle()) {
                $image = $image->rotate($angle);
            }

            if (true == $spec->getStrip()) {
                $image = $image->strip();
            }

            $options = array(
                'quality'          => $spec->getQuality(),
                'resolution-units' => $spec->getResolutionUnit(),
                'resolution-x'     => $spec->getResolutionX(),
                'resolution-y'     => $spec->getResolutionY(),
            );

            $image->save($dest, $options);

            if ($to_remove) {
                unlink($to_remove);
                rmdir(dirname($to_remove));
            }
        } catch (\MediaVorus\Exception\Exception $e) {
            throw new Exception\RuntimeException($e->getMessage(), $e->getCode(), $e);
        } catch (\PHPExiftool\Exception\Exception $e) {
            throw new Exception\RuntimeException($e->getMessage(), $e->getCode(), $e);
        } catch (\Imagine\Exception\Exception $e) {
            throw new Exception\RuntimeException($e->getMessage(), $e->getCode(), $e);
        }
    }

    protected function extractEmbeddedImage($pathfile)
    {
        $tmpDir = sys_get_temp_dir() . '/extractor' . mt_rand(1000000, 9999999);

        mkdir($tmpDir);

        $files = $this->container['exiftool.preview-extractor']->extract($pathfile, $tmpDir);

        $to_unlink = array();
        $selected = null;
        $size = null;

        foreach ($files as $file) {
            if ($file->isDir() || $file->isDot()) {
                continue;
            }

            if (is_null($selected) || $file->getSize() > $size) {
                $selected = $file->getPathname();
                $size = $file->getSize();
            } else {
                array_push($to_unlink, $file->getPathname());
            }
        }

        foreach ($to_unlink as $pathname) {
            if ($pathname != $selected) {
                unlink($pathname);
            }
        }

        if ($selected) {
            return $this->container['mediavorus']->guess($selected);
        }

        rmdir($tmpDir);

        return null;
    }
}
