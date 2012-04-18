<?php

namespace MediAlchemyst;

use \Symfony\Component\DependencyInjection\ParameterBag\ParameterBag;
require_once dirname(__FILE__) . '/../../../src/MediAlchemyst/Alchemyst.php';

class AlchemystTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @var Alchemyst
     */
    protected $object;

    /**
     * @covers MediAlchemyst\Alchemyst::__construct
     */
    protected function setUp()
    {
        $this->object = new Alchemyst(new DriversContainer(new ParameterBag(array())));
    }

    /**
     * @covers MediAlchemyst\Alchemyst::open
     * @covers MediAlchemyst\Alchemyst::close
     */
    public function testOpen()
    {
        $this->object->open(__DIR__ . '/../../files/Audio.mp3');
        $this->object->close();
        $this->object->open(__DIR__ . '/../../files/Audio.mp3');
        $this->object->open(__DIR__ . '/../../files/Test.ogv');
    }

    /**
     * @covers MediAlchemyst\Alchemyst::open
     * @covers MediAlchemyst\Exception\FileNotFoundException
     * @expectedException MediAlchemyst\Exception\FileNotFoundException
     */
    public function testOpenUnknownFile()
    {
        $this->object->open(__DIR__ . '/../../files/invalid.file');
    }

    /**
     * @covers MediAlchemyst\Alchemyst::turnInto
     * @covers MediAlchemyst\Exception\LogicException
     * @expectedException MediAlchemyst\Exception\LogicException
     */
    public function testTurnIntoNoFile()
    {
        $specs = new Specification\Audio();

        $this->object->turnInto(__DIR__ . '/../../files/output', $specs);
    }

    /**
     * @covers MediAlchemyst\Alchemyst::turnInto
     * @covers MediAlchemyst\Alchemyst::routeAction
     */
    public function testTurnInto()
    {
        $this->object->open(__DIR__ . '/../../files/Audio.mp3');

        $specs = new Specification\Audio();

        $dest = __DIR__ . '/../../files/output.flac';

        $this->object->turnInto($dest, $specs);


    }

}
