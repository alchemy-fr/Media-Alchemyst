<?php

namespace MediaAlchemyst\Driver;

require_once dirname(__FILE__) . '/../../../../src/MediaAlchemyst/Driver/ExiftoolExtractor.php';

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
        $logger = new \Monolog\Logger('test');

        $this->object = new ExiftoolExtractor($logger);
    }


    /**
     * @covers MediaAlchemyst\Driver\ExiftoolExtractor::getDriver
     */
    public function testGetDriver()
    {
        $this->assertInstanceOf('\\PHPExiftool\\PreviewExtractor', $this->object->getDriver());
    }

}
