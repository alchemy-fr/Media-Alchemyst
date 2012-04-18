<?php

namespace MediaAlchemyst;

use \Symfony\Component\DependencyInjection\ParameterBag\ParameterBag;

class AlchemystTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @var Alchemyst
     */
    protected $object;

    /**
     * @covers MediaAlchemyst\Alchemyst::__construct
     */
    protected function setUp()
    {
        $this->object = new Alchemyst(new DriversContainer(new ParameterBag(array())));
    }

    /**
     * @covers MediaAlchemyst\Alchemyst::open
     * @covers MediaAlchemyst\Alchemyst::close
     */
    public function testOpen()
    {
        $this->object->open(__DIR__ . '/../../files/Audio.mp3');
        $this->object->close();
        $this->object->open(__DIR__ . '/../../files/Audio.mp3');
        $this->object->open(__DIR__ . '/../../files/Test.ogv');
    }

    /**
     * @covers MediaAlchemyst\Alchemyst::open
     * @covers MediaAlchemyst\Exception\FileNotFoundException
     * @expectedException MediaAlchemyst\Exception\FileNotFoundException
     */
    public function testOpenUnknownFile()
    {
        $this->object->open(__DIR__ . '/../../files/invalid.file');
    }

    /**
     * @covers MediaAlchemyst\Alchemyst::turnInto
     * @covers MediaAlchemyst\Exception\LogicException
     * @expectedException MediaAlchemyst\Exception\LogicException
     */
    public function testTurnIntoNoFile()
    {
        $specs = new Specification\Audio();

        $this->object->turnInto(__DIR__ . '/../../files/output', $specs);
    }

    /**
     * @covers MediaAlchemyst\Alchemyst::turnInto
     * @covers MediaAlchemyst\Alchemyst::routeAction
     */
    public function testTurnInto()
    {
        $this->object->open(__DIR__ . '/../../files/Audio.mp3');

        $specs = new Specification\Audio();

        $dest = __DIR__ . '/../../files/output.flac';

        $this->object->turnInto($dest, $specs);


    }

}
