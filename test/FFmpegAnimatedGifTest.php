<?php
require_once '../phpunit/PHPUnit/Framework.php';
require_once '../FFmpegMovie.php';
require_once '../FFmpegFrame.php';
require_once '../FFmpegAnimatedGif.php';

/**
* Testing framework: PHPUnit (http://www.phpunit.de)
* 
* Create directory "phpunit" one level above this file.
* Unpack PHPUnit downloaded from http://pear.phpunit.de/get/
* to "phpunit" directory created earlier.
* 
* To run the test supposing that you are in the same
* directory as this file(FFmpegAnimatedGifTest.php) type:
* 
* php ../phpunit/phpunit.php FFmpegAnimatedGifTest.php
*/

class FFmpegAnimatedGifTest extends PHPUnit_Framework_TestCase {

    protected static $outFilePath;
    protected static $anim;
    
    public static function setUpBeforeClass() {
        self::$outFilePath = sys_get_temp_dir().uniqid('anim', true).'.gif';        
    }    
    
    public function testAddFrame() {
        $movie        = new FFmpegMovie('data/test.mp4');
        $frame        = $movie->getFrame(1);
        $memoryBefore = memory_get_usage();

        self::$anim        = new FFmpegAnimatedGif(self::$outFilePath, 100, 120, 1, 0);     
        self::$anim->addFrame($frame);
        
        $memoryAfter  = memory_get_usage();
        
        $this->assertGreaterThan($memoryBefore, $memoryAfter, 'Memory usage should be higher after adding frame');
    }
    
    public function testGetAnimation() {
        $movie        = new FFmpegMovie('data/test.mp4');
        $frame1       = $movie->getFrame(1);        
        $frame2       = $movie->getFrame(2);
        
        self::$anim = new FFmpegAnimatedGif(self::$outFilePath, 100, 120, 1, 0);
        self::$anim->addFrame($frame1); $frame1 = null;
        self::$anim->addFrame($frame2); $frame2 = null;
        
        $animData = self::$anim->getAnimation();
        $this->assertEquals(20526, strlen($animData), 'Animation binary size should be int(20526)');
    }
    
    public function testSave() {
        $movie        = new FFmpegMovie('data/test.mp4');
        $frame1       = $movie->getFrame(1);        
        $frame2       = $movie->getFrame(2);
        
        self::$anim = new FFmpegAnimatedGif(self::$outFilePath, 100, 120, 1, 0);
        self::$anim->addFrame($frame1); $frame1 = null;
        self::$anim->addFrame($frame2); $frame2 = null;

        $saveResult = self::$anim->save();
        $this->assertEquals(true, $saveResult, 'Save result should be true');
        $this->assertEquals(true, file_exists(self::$outFilePath), 'File "'.self::$outFilePath.'" should exist after saving');      
        $this->assertEquals(20526, filesize(self::$outFilePath), 'Animation binary size should be int(20526)');
        $imageInfo = getimagesize(self::$outFilePath);
        $this->assertEquals(100, $imageInfo[0], 'Saved image width should be int(100)');
        $this->assertEquals(120, $imageInfo[1], 'Saved image height should be int(120)');
        unlink(self::$outFilePath);
    }
    
    public function testSerializeUnserialize() {
        $movie        = new FFmpegMovie('data/test.mp4');
        $frame1       = $movie->getFrame(1);        
        $frame2       = $movie->getFrame(2);
        
        self::$anim = new FFmpegAnimatedGif(self::$outFilePath, 100, 120, 1, 0);
        self::$anim->addFrame($frame1); $frame1 = null;
        self::$anim->addFrame($frame2); $frame2 = null;
        
        
        $serialized  = serialize(self::$anim);
        self::$anim = null;
        self::$anim = unserialize($serialized);

        $saveResult = self::$anim->save();
        $this->assertEquals(true, $saveResult, 'Save result should be true');
        $this->assertEquals(true, file_exists(self::$outFilePath), 'File "'.self::$outFilePath.'" should exist after saving');      
        $this->assertEquals(20526, filesize(self::$outFilePath), 'Animation binary size should be int(20526)');
        $imageInfo = getimagesize(self::$outFilePath);
        $this->assertEquals(100, $imageInfo[0], 'Saved image width should be int(100)');
        $this->assertEquals(120, $imageInfo[1], 'Saved image height should be int(120)');
        unlink(self::$outFilePath);
    }       
    
    public static function tearDownAfterClass() {
        self::$anim        = null;
        self::$outFilePath = null;
    }    
}  
?>