<?php
/**
 * Testing framework: PHPUnit (http://www.phpunit.de)
 *
 * 1.) Install phpunit on your operating system
 * 2.) Run the test
 * 
 * phpunit --bootstrap test/bootstrap.php test/FFmpegAutoloaderTest.php
 */
/**
 * FFmpegAutoloaderTest contains tests for FFmpegAutoloader class
 * 
 * @author char0n (Vladimír Gorej, gorej@codescale.net)
 * @category tests
 * @package FFmpegPHP
 * @license New BSD
 * @version 2.6
 */
class FFmpegAutoloaderTest extends PHPUnit_Framework_TestCase {
    
    public function testAutoload() {
        $this->assertTrue(FFmpegAutoloader::autoload('FFmpegAnimatedGif'));
        $this->assertTrue(FFmpegAutoloader::autoload('FFmpegFrame'));   
        $this->assertTrue(FFmpegAutoloader::autoload('FFmpegMovie'));    
        $this->assertTrue(FFmpegAutoloader::autoload('ffmpeg_animated_gif'));    
        $this->assertTrue(FFmpegAutoloader::autoload('ffmpeg_frame'));    
        $this->assertTrue(FFmpegAutoloader::autoload('ffmpeg_movie'));    
        $this->assertTrue(FFmpegAutoloader::autoload('OutputProvider'));    
        $this->assertTrue(FFmpegAutoloader::autoload('AbstractOutputProvider'));    
        $this->assertTrue(FFmpegAutoloader::autoload('FFmpegOutputProvider'));    
        $this->assertTrue(FFmpegAutoloader::autoload('FFprobeOutputProvider'));    
        $this->assertFalse(FFmpegAutoloader::autoload(uniqid()));         
    }
}