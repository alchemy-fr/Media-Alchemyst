<?php

namespace MediaAlchemyst\Transmuter;

use MediaAlchemyst\Specification\Provider as Specification;
use MediaAlchemyst\DriversContainer;
use \MediaVorus\Media\Media;

abstract class Provider
{

    /**
     *
     * @var \MediaAlchemyst\DriversContainer
     */
    protected $container;

    public function __construct(DriversContainer $container)
    {
        $this->container = $container;
    }

    abstract public function execute(Specification $spec, Media $source, $dest);

}
