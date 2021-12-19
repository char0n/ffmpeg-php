<?php
namespace Char0n\FFMpegPHP\Tests\OutputProviders;

use PHPUnit\Framework\TestCase;
use SebastianBergmann\Timer\Timer;
use Char0n\FFMpegPHP\OutputProviders\FFMpegProvider;

class FFMpegProviderTest extends TestCase
{

    protected static $moviePath;

    protected static $movieUrl;
    /**
     * @var FFMpegProvider
     */
    protected $provider;

    public static function setUpBeforeClass(): void
    {
        $path = dirname(__FILE__).DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'data'.DIRECTORY_SEPARATOR;
        self::$moviePath = realpath($path.'test.mp4');
        self::$movieUrl = array(
            'base' => 'https://github.com/char0n/ffmpeg-php/blob/master/tests/data/',
            'fileName' => 'test',
            'fileExtension' => '.mp4',
            'query' => '?raw=true'
        );
    }

    public static function tearDownAfterClass(): void
    {
        self::$moviePath   = null;
    }

    public function setUp(): void
    {
        $this->provider = new FFMpegProvider();
        $this->provider->setMovieFile(self::$moviePath);
    }

    public function tearDown(): void
    {
        $this->provider = null;
    }

    public function testGetOutput()
    {
        $output = $this->provider->getOutput();
        $this->assertEquals(1, preg_match('/FFmpeg version/i', $output));
    }

    public function testGetOutputFileDoesNotExist()
    {
        $this->expectException(\UnexpectedValueException::class);
        $this->expectExceptionCode(334561);

        $provider = new FFMpegProvider();
        $provider->setMovieFile(uniqid('test', true));
        $provider->getOutput();
    }

    public function testGetOutputUrl()
    {
        $provider = new FFMpegProvider();
        $provider->setMovieFile(implode(self::$movieUrl));
        $output = $provider->getOutput();

        $this->assertEquals(1, preg_match('/FFmpeg version/i', $output));
    }

    public function testGetOutputUrlFileDoesNotExist()
    {
        $this->expectException(\UnexpectedValueException::class);
        $this->expectExceptionCode(334561);

        $provider = new FFMpegProvider();
        $nonExistingUrlFile = sprintf(
            '%s%s%s%s',
            self::$movieUrl['base'],
            uniqid('test', true),
            self::$movieUrl['fileExtension'],
            self::$movieUrl['query']
        );
        $provider->setMovieFile($nonExistingUrlFile);
        $provider->getOutput();
    }

    public function testPersistentResourceSimulation()
    {
        Timer::start();
        $provider = new FFMpegProvider('ffmpeg', true);
        $provider->setMovieFile(self::$moviePath);
        $provider->getOutput();
        $provider = clone $provider;
        $provider->getOutput();
        $provider = clone $provider;
        $provider->getOutput();
        $elapsed = Timer::stop();

        Timer::start();
        $provider = new FFMpegProvider('ffmpeg', false);
        $provider->setMovieFile(self::$moviePath);
        $provider->getOutput();
        $provider = clone $provider;
        $provider->getOutput();
        $provider = clone $provider;
        $provider->getOutput();
        $elapsed1 = Timer::stop();
        $this->assertGreaterThan($elapsed, $elapsed1, 'Persistent resource simulation should be faster');
    }

    public function testSerializeUnserialize()
    {
        $output = $this->provider->getOutput();
        $serialized  = serialize($this->provider);
        $this->provider = null;
        $this->provider = unserialize($serialized);
        $this->assertEquals(
            $output,
            $this->provider->getOutput(),
            'Output from original and unserialized provider should be equal'
        );
    }
}
