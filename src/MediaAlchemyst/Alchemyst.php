<?php

namespace MediaAlchemyst;

use MediaAlchemyst\Exception\FileNotFoundException;
use MediaVorus\MediaVorus;
use MediaVorus\File as MediaVorusFile;
use MediaVorus\Media\MediaInterface;
use MediaVorus\Exception\FileNotFoundException as MediaVorusFileNotFoundException;
use MediaAlchemyst\Specification\Specification;

class Alchemyst
{

    /**
     *
     * @var \MediaVorus\media\MediaMediaInterface
     */
    protected $mediaFile;

    /**
     *
     * @var \MediaAlchemyst\DriversContainer
     */
    protected $drivers;

    public function __construct(DriversContainer $container)
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

    public function turnInto($pathfile_dest, Specification $specs)
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

    protected function routeAction($pathfile_dest, Specification $specs)
    {
        $route = sprintf('%s-%s', $this->mediaFile->getType(), $specs->getType());

        switch ($route) {
            case sprintf('%s-%s', MediaInterface::TYPE_AUDIO, Specification::TYPE_IMAGE):
                throw new Exception\RuntimeException('Not transmuter avalaible... Implement it !');
                break;
            case sprintf('%s-%s', MediaInterface::TYPE_AUDIO, Specification::TYPE_VIDEO):
                throw new Exception\RuntimeException('Not transmuter avalaible... Implement it !');
                break;
            case sprintf('%s-%s', MediaInterface::TYPE_AUDIO, Specification::TYPE_AUDIO):
                $transmuter = new Transmuter\Audio2Audio($this->drivers);
                break;

            case sprintf('%s-%s', MediaInterface::TYPE_FLASH, Specification::TYPE_IMAGE):
                $transmuter = new Transmuter\Flash2Image($this->drivers);
                break;

            case sprintf('%s-%s', MediaInterface::TYPE_DOCUMENT, Specification::TYPE_IMAGE):
                $transmuter = new Transmuter\Document2Image($this->drivers);
                break;
            case sprintf('%s-%s', MediaInterface::TYPE_DOCUMENT, Specification::TYPE_SWF):
                $transmuter = new Transmuter\Document2Flash($this->drivers);
                break;

            case sprintf('%s-%s', MediaInterface::TYPE_IMAGE, Specification::TYPE_IMAGE):
                $transmuter = new Transmuter\Image2Image($this->drivers);
                break;

            case sprintf('%s-%s', MediaInterface::TYPE_VIDEO, Specification::TYPE_IMAGE):
                $transmuter = new Transmuter\Video2Image($this->drivers);
                break;
            case sprintf('%s-%s', MediaInterface::TYPE_VIDEO, Specification::TYPE_ANIMATION):
                $transmuter = new Transmuter\Video2Animation($this->drivers);
                break;
            case sprintf('%s-%s', MediaInterface::TYPE_VIDEO, Specification::TYPE_VIDEO):
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
