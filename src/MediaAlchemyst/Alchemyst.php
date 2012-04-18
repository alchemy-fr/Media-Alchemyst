<?php

namespace MediaAlchemyst;

use MediaVorus;
use MediaVorus\Media\Media;
use MediaAlchemyst\Specification\Specification;

class Alchemyst
{

    /**
     *
     * @var \MediaVorus\media\Media
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
        if ($this->mediaFile)
        {
            $this->close();
        }

        try
        {
            $this->mediaFile = MediaVorus\MediaVorus::guess(new MediaVorus\File($pathfile, true));
        }
        catch (MediaVorus\Exception\FileNotFoundException $e)
        {
            throw new Exception\FileNotFoundException(sprintf('File %s not found', $pathfile));
        }
    }

    public function turnInto($pathfile_dest, Specification $specs)
    {
        if ( ! $this->mediaFile)
        {
            throw new Exception\LogicException('You must open a file before transmute it');
        }

        $this->routeAction($pathfile_dest, $specs);
    }

    public function close()
    {
        $this->mediaFile = null;
    }

    protected function routeAction($pathfile_dest, Specification $specs)
    {
        $route = sprintf('%s-%s', $this->mediaFile->getType(), $specs->getType());

        switch ($route)
        {
            case sprintf('%s-%s', Media::TYPE_AUDIO, Specification::TYPE_IMAGE):
                throw new Exception\RuntimeException('Not transmuter avalaible... Implement it !');
                break;
            case sprintf('%s-%s', Media::TYPE_AUDIO, Specification::TYPE_VIDEO):
                throw new Exception\RuntimeException('Not transmuter avalaible... Implement it !');
                break;
            case sprintf('%s-%s', Media::TYPE_AUDIO, Specification::TYPE_AUDIO):
                $transmuter = new Transmuter\Audio2Audio($this->drivers);
                break;

            case sprintf('%s-%s', Media::TYPE_FLASH, Specification::TYPE_IMAGE):
                $transmuter = new Transmuter\Flash2Image($this->drivers);
                break;

            case sprintf('%s-%s', Media::TYPE_DOCUMENT, Specification::TYPE_IMAGE):
                $transmuter = new Transmuter\Document2Image($this->drivers);
                break;
            case sprintf('%s-%s', Media::TYPE_DOCUMENT, Specification::TYPE_SWF):
                $transmuter = new Transmuter\Document2Flash($this->drivers);
                break;

            case sprintf('%s-%s', Media::TYPE_IMAGE, Specification::TYPE_IMAGE):
                $transmuter = new Transmuter\Image2Image($this->drivers);
                break;

            case sprintf('%s-%s', Media::TYPE_VIDEO, Specification::TYPE_IMAGE):
                $transmuter = new Transmuter\Video2Image($this->drivers);
                break;
            case sprintf('%s-%s', Media::TYPE_VIDEO, Specification::TYPE_VIDEO):
                $transmuter = new Transmuter\Video2Video($this->drivers);
                break;
            case sprintf('%s-%s', Media::TYPE_VIDEO, Specification::TYPE_AUDIO):
                throw new Exception\RuntimeException('Not transmuter avalaible... Implement it !');
                break;

            default:
                throw new Exception\RuntimeException('Not transmuter avalaible... Implement it !');
                break;
        }

        $transmuter->execute($specs, $this->mediaFile, $pathfile_dest);
    }

}
