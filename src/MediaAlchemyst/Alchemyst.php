<?php

namespace MediaAlchemyst;

use MediaVorus\Media\MediaInterface;
use MediaVorus\Exception\FileNotFoundException as MediaVorusFileNotFoundException;
use MediaAlchemyst\Exception\FileNotFoundException;
use MediaAlchemyst\Exception\LogicException;
use MediaAlchemyst\Exception\RuntimeException;
use MediaAlchemyst\Specification\SpecificationInterface;
use MediaAlchemyst\Transmuter\Audio2Audio;
use MediaAlchemyst\Transmuter\Document2Flash;
use MediaAlchemyst\Transmuter\Document2Image;
use MediaAlchemyst\Transmuter\Flash2Image;
use MediaAlchemyst\Transmuter\Image2Image;
use MediaAlchemyst\Transmuter\Video2Animation;
use MediaAlchemyst\Transmuter\Video2Image;
use MediaAlchemyst\Transmuter\Video2Video;
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
            throw new LogicException('You must open a file before transmute it');
        }

        $this->routeAction($pathfile_dest, $specs);

        return $this;
    }

    public function close()
    {
        $this->mediaFile = null;

        return $this;
    }

    public static function create()
    {
        return new static(DriversContainer::create());
    }

    private function routeAction($pathfile_dest, SpecificationInterface $specs)
    {
        $route = sprintf('%s-%s', $this->mediaFile->getType(), $specs->getType());

        switch ($route) {
            case sprintf('%s-%s', MediaInterface::TYPE_AUDIO, SpecificationInterface::TYPE_IMAGE):
                throw new RuntimeException('Not transmuter avalaible... Implement it !');
                break;
            case sprintf('%s-%s', MediaInterface::TYPE_AUDIO, SpecificationInterface::TYPE_VIDEO):
                throw new RuntimeException('Not transmuter avalaible... Implement it !');
                break;
            case sprintf('%s-%s', MediaInterface::TYPE_AUDIO, SpecificationInterface::TYPE_AUDIO):
                $transmuter = new Audio2Audio($this->drivers);
                break;

            case sprintf('%s-%s', MediaInterface::TYPE_FLASH, SpecificationInterface::TYPE_IMAGE):
                $transmuter = new Flash2Image($this->drivers);
                break;

            case sprintf('%s-%s', MediaInterface::TYPE_DOCUMENT, SpecificationInterface::TYPE_IMAGE):
                $transmuter = new Document2Image($this->drivers);
                break;
            case sprintf('%s-%s', MediaInterface::TYPE_DOCUMENT, SpecificationInterface::TYPE_SWF):
                $transmuter = new Document2Flash($this->drivers);
                break;

            case sprintf('%s-%s', MediaInterface::TYPE_IMAGE, SpecificationInterface::TYPE_IMAGE):
                $transmuter = new Image2Image($this->drivers);
                break;

            case sprintf('%s-%s', MediaInterface::TYPE_VIDEO, SpecificationInterface::TYPE_IMAGE):
                $transmuter = new Video2Image($this->drivers);
                break;
            case sprintf('%s-%s', MediaInterface::TYPE_VIDEO, SpecificationInterface::TYPE_ANIMATION):
                $transmuter = new Video2Animation($this->drivers);
                break;
            case sprintf('%s-%s', MediaInterface::TYPE_VIDEO, SpecificationInterface::TYPE_VIDEO):
                $transmuter = new Video2Video($this->drivers);
                break;
            default:
                throw new RuntimeException(sprintf('Not transmuter avalaible for `%s` Implement it !', $route));
                break;
        }

        $transmuter->execute($specs, $this->mediaFile, $pathfile_dest);

        return $this;
    }

}
