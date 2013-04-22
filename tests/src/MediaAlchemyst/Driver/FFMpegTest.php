<?php

namespace MediaAlchemyst\Driver;

use Symfony\Component\Process\ExecutableFinder;
use Monolog\Logger;
use Monolog\Handler\NullHandler;

class FFMpegTest extends \PHPUnit_Framework_TestCase
{

    public function setUp()
    {
        $executableFinder = new ExecutableFinder();
        if ( ! $executableFinder->find('ffmpeg')) {
            $this->markTestSkipped('FFMpeg is not installed');
        }
    }

    protected function build($binary = null)
    {
        $logger = new Logger('test');
        $logger->pushHandler(new NullHandler());

        return new FFMpeg($logger, $binary);
    }

    /**
     * @covers MediaAlchemyst\Driver\FFMpeg::__construct
     */
    public function testConstruct()
    {
        $this->build();
    }

    public function testOptions()
    {
        $logger = new Logger('test');
        $logger->pushHandler(new NullHandler());

        $driver = new FFMpeg($logger, null, null, 4, 120);
        $ffmpeg = $driver->getDriver();

        $this->assertEquals(120, $ffmpeg->getTimeout());
        $this->assertEquals(4, $ffmpeg->getThreads());
    }

    public function testWithDefinedBinaryOptions()
    {
        $finder = new ExecutableFinder();

        $ffmpegBinary = $finder->find('ffmpeg');
        $ffprobeBinary = $finder->find('ffprobe');

        if (!$ffmpegBinary || !$ffprobeBinary) {
            $this->markTestSkipped('Unable to find required binaries for this test');
        }

        $logger = new Logger('test');
        $logger->pushHandler(new NullHandler());

        $driver = new FFMpeg($logger, $ffmpegBinary, $ffprobeBinary, 4, 120);
        $ffmpeg = $driver->getDriver();

        $this->assertEquals(120, $ffmpeg->getTimeout());
        $this->assertEquals(4, $ffmpeg->getThreads());
    }

    /**
     * @covers MediaAlchemyst\Driver\FFMpeg::__construct
     * @expectedException MediaAlchemyst\Exception\RuntimeException
     */
    public function testConstructOwnBinary()
    {
        $this->build('Albator');
    }

    /**
     * @covers MediaAlchemyst\Driver\FFMpeg::getDriver
     */
    public function testGetDriver()
    {
        $driver = $this->build();

        $this->assertInstanceOf('\\FFMpeg\\FFMpeg', $driver->getDriver());
    }

}
