<?php
/**
 * Testing framework: PHPUnit (http://www.phpunit.de)
 *
 * 1.) Install phpunit on your operating system
 * 2.) Run the test
 * 
 * phpunit --bootstrap test/bootstrap.php test/adapter/ffmpeg_animated_gif_test.php
 */
/**
 * ffmpeg_animated_gif_test contains tests for ffmpeg_animated_gif adapter class
 * 
 * @author char0n (Vladimír Gorej, gorej@codescale.net)
 * @category tests 
 * @package FFmpegPHP
 * @subpackage adapter
 * @license New BSD
 * @version 2.5
 */
class ffmpeg_animated_git_test extends PHPUnit_Framework_TestCase {

    protected static $outFilePath;
    protected static $moviePath;
    protected static $movie;
    protected static $frame1;
    protected static $frame2;
    protected static $anim;
    
    public static function setUpBeforeClass() {
        self::$outFilePath = sys_get_temp_dir().uniqid('anim', true).'.gif';        
        self::$moviePath   = '../data/test.mp4';
        self::$movie       = new ffmpeg_movie(self::$moviePath);
        self::$frame1      = self::$movie->getFrame(1);
        self::$frame2      = self::$movie->getFrame(2);
    }    
    
    public function testAddFrame() {
        $frame        = self::$movie->getFrame(3);
        $memoryBefore = memory_get_usage();

        self::$anim   = new ffmpeg_animated_gif(self::$outFilePath, 100, 120, 1, 0);     
        self::$anim->addFrame($frame);
        
        $memoryAfter  = memory_get_usage();
        
        $this->assertGreaterThan($memoryBefore, $memoryAfter, 'Memory usage should be higher after adding frame');
    }
    
    public function testSerializeUnserialize() {
        self::$anim = new ffmpeg_animated_gif(self::$outFilePath, 100, 120, 1, 0);
        self::$anim->addFrame(self::$frame1); 
        self::$anim->addFrame(self::$frame2);
        
        $serialized  = serialize(self::$anim);
        self::$anim = null;
        self::$anim = unserialize($serialized);

        $saveResult = self::$anim->addFrame(self::$frame1);
        $this->assertEquals(true, $saveResult, 'Save result should be true');
        $this->assertEquals(true, file_exists(self::$outFilePath), 'File "'.self::$outFilePath.'" should exist after saving');      
        $this->assertEquals(30035, filesize(self::$outFilePath), 'Animation binary size should be int(20503)');
        $imageInfo = getimagesize(self::$outFilePath);
        $this->assertEquals(100, $imageInfo[0], 'Saved image width should be int(100)');
        $this->assertEquals(120, $imageInfo[1], 'Saved image height should be int(120)');
        unlink(self::$outFilePath);
    }       
    
    public static function tearDownAfterClass() {
        self::$anim        = null;
        self::$outFilePath = null;
        self::$moviePath   = null;
        self::$movie       = null;
        self::$frame1      = null;
        self::$frame2      = null;
        self::$anim        = null;        
    }    
}  
?>