<?php

namespace MediaAlchemyst\Transmuter;

use Imagine\Exception\Exception as ImagineException;
use Imagine\Image\ImageInterface;
use MediaVorus\Exception\ExceptionInterface as MediaVorusException;
use MediaAlchemyst\Specification\Image;
use MediaAlchemyst\Specification\SpecificationInterface;
use MediaAlchemyst\Exception\SpecNotSupportedException;
use MediaAlchemyst\Exception\RuntimeException;
use MediaVorus\Media\MediaInterface;
use PHPExiftool\Exception\ExceptionInterface as PHPExiftoolException;

class Image2Image extends AbstractTransmuter
{
    public static $autorotate = false;
    public static $lookForEmbeddedPreview = false;

    public function execute(SpecificationInterface $spec, MediaInterface $source, $dest)
    {
        if ( ! $spec instanceof Image) {
            throw new SpecNotSupportedException('Imagine Adapter only supports Image specs');
        }

        if ($source->getType() !== MediaInterface::TYPE_IMAGE) {
            throw new SpecNotSupportedException('Imagine Adapter only supports Images');
        }

        try {
            $to_remove = array();

            if (static::$lookForEmbeddedPreview) {
                $tmpFile = $this->extractEmbeddedImage($source->getFile()->getPathname());

                if ($tmpFile instanceof MediaInterface) {
                    $source = $tmpFile;
                    $to_remove[] = $tmpFile->getFile()->getRealPath();
                    $to_remove[] = dirname($tmpFile->getFile()->getRealPath());
                }
            }

            if ($source->getFile()->getMimeType() === 'application/illustrator') {

                $tmpFile = tempnam(sys_get_temp_dir(), 'gs_transcoder');
                unlink($tmpFile);

                $this->container['ghostscript.transcoder']->open($source->getFile()->getRealPath())
                    ->toImage($tmpFile)
                    ->close();

                if (file_exists($tmpFile)) {
                    $source = $this->container['mediavorus']->guess($tmpFile);
                    $to_remove[] = $tmpFile;
                }
            } elseif ($source->getFile()->getMimeType() === 'image/tiff') {
                $image = $this->container['imagine']->open($source->getFile()->getRealPath());

                $layers = array();

                foreach ($image->layers() as $layer) {
                    $tmpFile = tempnam(sys_get_temp_dir(), 'imagine-tiff-layer');
                    unlink($tmpFile);

                    $tmpFile = $tmpFile . '.' . pathinfo($dest, PATHINFO_EXTENSION);
                    $layer->save($tmpFile);

                    $layers[] = $tmpFile;
                }

                uasort($layers, function ($layer1, $layer2) {
                    $size1 = filesize($layer1);
                    $size2 = filesize($layer2);
                    if ($size1 == $size2) {
                        return 0;
                    }

                    return ($size1 > $size2) ? -1 : 1;
                });

                $to_remove = array_merge($to_remove, $layers);

                $source = $this->container['mediavorus']->guess(array_shift($layers));
            }

            $image = $this->container['imagine']->open($source->getFile()->getPathname());

            if ($spec->getWidth() && $spec->getHeight()) {

                $box = $this->boxFromImageSpec($spec, $source);

                if ($spec->getResizeMode() == Image::RESIZE_MODE_OUTBOUND) {
                    /* @var $image \Imagine\Gmagick\Image */
                    $image = $image->thumbnail($box, ImageInterface::THUMBNAIL_OUTBOUND);
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
                'flatten'          => strtolower(pathinfo($dest, PATHINFO_EXTENSION)) === 'gif',
                'quality'          => $spec->getQuality(),
                'resolution-units' => $spec->getResolutionUnit(),
                'resolution-x'     => $spec->getResolutionX(),
                'resolution-y'     => $spec->getResolutionY(),
            );

            $image->save($dest, $options);

            foreach ($to_remove as $file) {
                if (is_dir($file)) {
                    rmdir($file);
                } else {
                    unlink($file);
                }
            }
        } catch (MediaVorusException $e) {
            throw new RuntimeException('Unable to transmute image to image due to Mediavorus', null, $e);
        } catch (PHPExiftoolException $e) {
            throw new RuntimeException('Unable to transmute image to image due to PHPExiftool', null, $e);
        } catch (ImagineException $e) {
            throw new RuntimeException('Unable to transmute image to image due to Imagine', null, $e);
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
