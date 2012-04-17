<?php

namespace MediAlchemyst\Transmuter;

use MediAlchemyst\Specification\Provider as Specification;
use MediAlchemyst\DriversContainer;
use \MediaVorus\Media\Media;

abstract class Provider
{

    /**
     *
     * @var \MediAlchemyst\DriversContainer
     */
    protected $container;

    public function __construct(DriversContainer $container)
    {
        $this->container = $container;
    }

    abstract public function execute(Specification $spec, Media $source, $dest);

}
