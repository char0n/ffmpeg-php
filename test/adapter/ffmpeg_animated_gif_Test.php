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
 * @version 2.6
 */
class ffmpeg_animated_git_test extends PHPUnit_Framework_TestCase {

    protected static $outFilePath;
    protected static $moviePath;
    protected $movie;
    protected $frame1;
    protected $frame2;
    protected $anim;
    
    public static function setUpBeforeClass() {
        self::$outFilePath = sys_get_temp_dir().DIRECTORY_SEPARATOR.uniqid('anim', true).'.gif';       
        self::$moviePath   = realpath(dirname(__FILE__).DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'data'.DIRECTORY_SEPARATOR.'test.mp4');
    }    

    public static function tearDownAfterClass() {
        self::$outFilePath = null;
        self::$moviePath   = null;
    }

    public function setUp() {
        $this->movie  = new ffmpeg_movie(self::$moviePath);
        $this->frame1 = $this->movie->getFrame(1);
        $this->frame2 = $this->movie->getFrame(2);
        $this->anim   = new ffmpeg_animated_gif(self::$outFilePath, 100, 120, 1, 0);
    }

    public function tearDown() {
        $this->movie  = null;
        $this->frame1 = null;
        $this->frame2 = null;   
        $this->anim   = null;
        if (file_exists(self::$outFilePath)) unlink(self::$outFilePath);
    }
    
    public function testAddFrame() {
        $frame        = $this->movie->getFrame(3);
        $memoryBefore = memory_get_usage();

        $this->anim->addFrame($frame);
        
        $memoryAfter  = memory_get_usage();
        
        $this->assertGreaterThan($memoryBefore, $memoryAfter, 'Memory usage should be higher after adding frame');
    }

    public function testSerializeUnserialize() {
        $this->anim->addFrame($this->frame1);
        $this->anim->addFrame($this->frame2);
        
        $serialized = serialize($this->anim);
        $this->anim = null;
        $this->anim = unserialize($serialized);

        $saveResult = $this->anim->addFrame($this->frame1);
        $this->assertEquals(true, $saveResult, 'Save result should be true');
        $this->assertEquals(true, file_exists(self::$outFilePath), 'File "'.self::$outFilePath.'" should exist after saving');
        $this->assertEquals(30585, filesize(self::$outFilePath), 'Animation binary size should be int(30585)');
        $imageInfo = getimagesize(self::$outFilePath);
        $this->assertEquals(100, $imageInfo[0], 'Saved image width should be int(100)');
        $this->assertEquals(120, $imageInfo[1], 'Saved image height should be int(120)');
    }     
}