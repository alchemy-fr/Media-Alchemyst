<?php

namespace MediaAlchemyst\Transmuter;

use Imagine\Image;
use MediaAlchemyst\Specification;
use MediaAlchemyst\Specification\Provider as SpecProvider;
use MediaAlchemyst\DriversContainer;
use MediaVorus\Media\MediaInterface;

abstract class Provider
{
    /**
     *
     * @var \MediaAlchemyst\DriversContainer
     */
    protected $container;

    public function __construct(\Pimple $container)
    {
        $this->container = $container;
    }

    public function __destruct()
    {
        $this->container = null;
    }

    /**
     * Return the box for a spec
     *
     * @param  Specification\Image $spec
     * @param  MediaInterface               $source
     * @return \Image\Box
     */
    protected function boxFromImageSpec(Specification\Image $spec, MediaInterface $source)
    {
        if ( ! $spec->getWidth() && ! $spec->getHeight()) {
            throw new \MediaAlchemyst\Exception\InvalidArgumentException('The specification you provide must have width nad height');
        }

        if ($spec->getResizeMode() == Specification\Image::RESIZE_MODE_INBOUND_FIXEDRATIO) {

            $ratioOut = $spec->getWidth() / $spec->getHeight();
            $ratioIn = $source->getWidth() / $source->getHeight();

            if ($ratioOut > $ratioIn) {

                $outHeight = round($spec->getHeight());
                $outWidth = round($ratioIn * $outHeight);
            } else {

                $outWidth = round($spec->getWidth());
                $outHeight = round($outWidth / $ratioIn);
            }

            return new Image\Box($outWidth, $outHeight);
        }

        return new Image\Box($spec->getWidth(), $spec->getHeight());
    }

    abstract public function execute(SpecProvider $spec, MediaInterface $source, $dest);
}
