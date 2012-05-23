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

    public function __destruct()
    {
        $this->container = null;
    }

    abstract public function execute(Specification $spec, Media $source, $dest);

}
