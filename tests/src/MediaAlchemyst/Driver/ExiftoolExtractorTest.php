<?php

namespace MediaAlchemyst\Driver;

use Monolog\Logger;
use PHPExiftool\Exiftool;

class ExiftoolExtractorTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @var ExiftoolExtractor
     */
    protected $object;


    /**
     * @covers MediaAlchemyst\Driver\ExiftoolExtractor::__construct
     */
    protected function setUp()
    {
        $logger = new Logger('test');

        $this->object = new ExiftoolExtractor(new Exiftool, $logger);
    }


    /**
     * @covers MediaAlchemyst\Driver\ExiftoolExtractor::getDriver
     */
    public function testGetDriver()
    {
        $this->assertInstanceOf('\\PHPExiftool\\PreviewExtractor', $this->object->getDriver());
    }

}
