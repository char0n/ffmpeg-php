<?php
/**
 * Testing framework: PHPUnit (http://www.phpunit.de)
 *
 * 1.) Install phpunit on your operating system
 * 2.) Run the test
 * 
 * phpunit --bootstrap test/bootstrap.php test/provider/FFmpegOutputProviderTest.php
 */
/**
 * FFmpegOutputProviderTest contains tests for FFmpegOutputProvider class
 * 
 * @author char0n (Vladimír Gorej, gorej@codescale.net)
 * @category tests
 * @package FFmpegPHP
 * @subpackage provider
 * @license New BSD
 * @version 2.6
 */

class FFmpegOutputProviderTest extends PHPUnit_Framework_TestCase {
    
    protected static $moviePath;
    protected $provider;

    public static function setUpBeforeClass() {
        self::$moviePath = realpath(dirname(__FILE__).DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'data'.DIRECTORY_SEPARATOR.'test.mp4');
    }

    public static function tearDownAfterClass() {
        self::$moviePath   = null;
    }

    public function setUp() {
        $this->provider = new FFmpegOutputProvider();
        $this->provider->setMovieFile(self::$moviePath);
    }

    public function tearDown() {
        $this->provider = null;
    }

    public function testGetOutput() {
        $output = $this->provider->getOutput();
        $this->assertEquals(1, preg_match('/FFmpeg version/i', $output));
    }   

    public function testGetOutputFileDoesntExist() {
        try {
            $provider = new FFmpegOutputProvider();
            $provider->setMovieFile(uniqid('test', true));
            $provider->getOutput();
        } catch (Exception $ex) {
            if ($ex->getCode() == 334561) {
                return;
            } else {
                $this->fail('Expected exception raise with wrong code');
            }
        }

        $this->fail('An expected exception with code 334561 has not been raised');
    }

    public function testPersistentResourceSimulation() {
        PHP_Timer::start();
        $provider = new FFmpegOutputProvider('ffmpeg', true);
        $provider->setMovieFile(self::$moviePath);
        $provider->getOutput();
        $provider = clone $provider;
        $provider->getOutput();
        $provider = clone $provider;
        $provider->getOutput();
        $elapsed = PHP_Timer::stop();
        
        PHP_Timer::start();
        $provider = new FFmpegOutputProvider('ffmpeg', false);
        $provider->setMovieFile(self::$moviePath);
        $provider->getOutput();
        $provider = clone $provider;
        $provider->getOutput();
        $provider = clone $provider;
        $provider->getOutput();
        $elapsed1 = PHP_Timer::stop();
        $this->assertGreaterThan($elapsed, $elapsed1, 'Persistent resource simulation should be faster');
    }

    public function testSerializeUnserialize() {
        $output = $this->provider->getOutput();
        $serialized  = serialize($this->provider);
        $this->provider = null;
        $this->provider = unserialize($serialized);
        $this->assertEquals($output, $this->provider->getOutput(), 'Output from original and unserialized provider should be equal');        
    }
}