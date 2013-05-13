<?php
/**
 * Testing framework: PHPUnit (http://www.phpunit.de)
 *
 * 1.) Install phpunit on your operating system
 * 2.) Run the test
 * 
 * phpunit --bootstrap test/bootstrap.php test/FFmpegAnimatedGifTest.php
 */
/**
 * FFmpegAnimatedGifTest contains tests for FFmpegAnimatedGif class
 * 
 * @author char0n (Vladimír Gorej, gorej@codescale.net)
 * @category tests
 * @package FFmpegPHP
 * @license New BSD
 * @version 2.6
 */
class FFmpegAnimatedGifTest extends PHPUnit_Framework_TestCase {

    protected static $outFilePath;
    protected static $moviePath;
    protected $movie;
    protected $frame1;
    protected $frame2;
    protected $anim;
    
    public static function setUpBeforeClass() {
        self::$outFilePath = sys_get_temp_dir().DIRECTORY_SEPARATOR.uniqid('anim', true).'.gif';        
        self::$moviePath   = dirname(__FILE__).DIRECTORY_SEPARATOR.'data'.DIRECTORY_SEPARATOR.'test.mp4';
    }   

    public static function tearDownAfterClass() {
        self::$outFilePath = null;
        self::$moviePath   = null;
    }

    public function setUp() {
        $this->movie  = new FFmpegMovie(self::$moviePath);
        $this->frame1 = $this->movie->getFrame(1);
        $this->frame2 = $this->movie->getFrame(2);
        $this->anim   = new FFmpegAnimatedGif(self::$outFilePath, 100, 120, 1, 0);
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
    
    public function testGetAnimation() {
        $this->anim->addFrame($this->frame1); 
        $this->anim->addFrame($this->frame2); 
        
        $animData = $this->anim->getAnimation();
        $this->assertEquals(20936, strlen($animData), 'Animation binary size should be int(20936)');
    }
    
    public function testSave() {
        $this->anim->addFrame($this->frame1);
        $this->anim->addFrame($this->frame2);

        $saveResult = $this->anim->save();
        $this->assertEquals(true, $saveResult, 'Save result should be true');
        $this->assertEquals(true, file_exists(self::$outFilePath), 'File "'.self::$outFilePath.'" should exist after saving');      
        $this->assertEquals(20936, filesize(self::$outFilePath), 'Animation binary size should be int(20936)');
        $imageInfo = getimagesize(self::$outFilePath);
        $this->assertEquals(100, $imageInfo[0], 'Saved image width should be int(100)');
        $this->assertEquals(120, $imageInfo[1], 'Saved image height should be int(120)');
    }
    
    public function testSerializeUnserialize() {
        $this->anim->addFrame($this->frame1); 
        $this->anim->addFrame($this->frame2);
        
        $serialized  = serialize($this->anim);
        $this->anim = null;
        $this->anim = unserialize($serialized);

        $saveResult = $this->anim->save();
        $this->assertEquals(true, $saveResult, 'Save result should be true');
        $this->assertEquals(true, file_exists(self::$outFilePath), 'File "'.self::$outFilePath.'" should exist after saving');      
        $this->assertEquals(20936, filesize(self::$outFilePath), 'Animation binary size should be int(20936)');
        $imageInfo = getimagesize(self::$outFilePath);
        $this->assertEquals(100, $imageInfo[0], 'Saved image width should be int(100)');
        $this->assertEquals(120, $imageInfo[1], 'Saved image height should be int(120)');
    }       
}