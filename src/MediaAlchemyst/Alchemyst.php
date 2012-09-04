<?php

namespace MediaAlchemyst;

use MediaAlchemyst\Exception\FileNotFoundException;
use MediaVorus\MediaVorus;
use MediaVorus\File as MediaVorusFile;
use MediaVorus\Media\MediaInterface;
use MediaVorus\Exception\FileNotFoundException as MediaVorusFileNotFoundException;
use MediaAlchemyst\Specification\SpecificationInterface;
use Pimple;

class Alchemyst
{

    /**
     *
     * @var \MediaVorus\media\MediaMediaInterface
     */
    protected $mediaFile;

    /**
     *
     * @var Pimple
     */
    protected $drivers;

    public function __construct(Pimple $container)
    {
        $this->drivers = $container;
    }

    public function open($pathfile)
    {
        if ($this->mediaFile) {
            $this->close();
        }

        try {
            $this->mediaFile = $this->drivers['mediavorus']->guess($pathfile);
        } catch (MediaVorusFileNotFoundException $e) {
            throw new FileNotFoundException(sprintf('File %s not found', $pathfile));
        }

        return $this;
    }

    public function turnInto($pathfile_dest, SpecificationInterface $specs)
    {
        if ( ! $this->mediaFile) {
            throw new Exception\LogicException('You must open a file before transmute it');
        }

        $this->routeAction($pathfile_dest, $specs);

        return $this;
    }

    public function close()
    {
        $this->mediaFile = null;

        return $this;
    }

    protected function routeAction($pathfile_dest, SpecificationInterface $specs)
    {
        $route = sprintf('%s-%s', $this->mediaFile->getType(), $specs->getType());

        switch ($route) {
            case sprintf('%s-%s', MediaInterface::TYPE_AUDIO, SpecificationInterface::TYPE_IMAGE):
                throw new Exception\RuntimeException('Not transmuter avalaible... Implement it !');
                break;
            case sprintf('%s-%s', MediaInterface::TYPE_AUDIO, SpecificationInterface::TYPE_VIDEO):
                throw new Exception\RuntimeException('Not transmuter avalaible... Implement it !');
                break;
            case sprintf('%s-%s', MediaInterface::TYPE_AUDIO, SpecificationInterface::TYPE_AUDIO):
                $transmuter = new Transmuter\Audio2Audio($this->drivers);
                break;

            case sprintf('%s-%s', MediaInterface::TYPE_FLASH, SpecificationInterface::TYPE_IMAGE):
                $transmuter = new Transmuter\Flash2Image($this->drivers);
                break;

            case sprintf('%s-%s', MediaInterface::TYPE_DOCUMENT, SpecificationInterface::TYPE_IMAGE):
                $transmuter = new Transmuter\Document2Image($this->drivers);
                break;
            case sprintf('%s-%s', MediaInterface::TYPE_DOCUMENT, SpecificationInterface::TYPE_SWF):
                $transmuter = new Transmuter\Document2Flash($this->drivers);
                break;

            case sprintf('%s-%s', MediaInterface::TYPE_IMAGE, SpecificationInterface::TYPE_IMAGE):
                $transmuter = new Transmuter\Image2Image($this->drivers);
                break;

            case sprintf('%s-%s', MediaInterface::TYPE_VIDEO, SpecificationInterface::TYPE_IMAGE):
                $transmuter = new Transmuter\Video2Image($this->drivers);
                break;
            case sprintf('%s-%s', MediaInterface::TYPE_VIDEO, SpecificationInterface::TYPE_ANIMATION):
                $transmuter = new Transmuter\Video2Animation($this->drivers);
                break;
            case sprintf('%s-%s', MediaInterface::TYPE_VIDEO, SpecificationInterface::TYPE_VIDEO):
                $transmuter = new Transmuter\Video2Video($this->drivers);
                break;
            default:
                throw new Exception\RuntimeException(sprintf('Not transmuter avalaible for `%s` Implement it !', $route));
                break;
        }

        $transmuter->execute($specs, $this->mediaFile, $pathfile_dest);

        return $this;
    }

}
