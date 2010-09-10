<?php
 /**
* Testing framework: PHPUnit (http://www.phpunit.de)
* 
* Create directory "phpunit" one level above this file.
* Unpack PHPUnit downloaded from http://pear.phpunit.de/get/
* to "phpunit" directory created earlier.
* 
* To run the test run the command:
* 
* php phpunit/phpunit.php --bootstrap test/bootstrap.php test/FFmpegAnimatedGifTest.php
*/

class FFmpegAnimatedGifTest extends PHPUnit_Framework_TestCase {

    protected static $outFilePath;
    protected static $moviePath;
    protected static $movie;
    protected static $frame1;
    protected static $frame2;
    protected static $anim;
    
    public static function setUpBeforeClass() {
        self::$outFilePath = sys_get_temp_dir().uniqid('anim', true).'.gif';        
        self::$moviePath   = dirname(__FILE__).DIRECTORY_SEPARATOR.'data/test.mp4';
        self::$movie       = new FFmpegMovie(self::$moviePath);
        self::$frame1      = self::$movie->getFrame(1);
        self::$frame2      = self::$movie->getFrame(2);
    }    
    
    public function testAddFrame() {
        $frame        = self::$movie->getFrame(3);
        $memoryBefore = memory_get_usage();

        self::$anim   = new FFmpegAnimatedGif(self::$outFilePath, 100, 120, 1, 0);     
        self::$anim->addFrame($frame);
        
        $memoryAfter  = memory_get_usage();
        
        $this->assertGreaterThan($memoryBefore, $memoryAfter, 'Memory usage should be higher after adding frame');
    }
    
    public function testGetAnimation() {
        self::$anim = new FFmpegAnimatedGif(self::$outFilePath, 100, 120, 1, 0);
        self::$anim->addFrame(self::$frame1); 
        self::$anim->addFrame(self::$frame2); 
        
        $animData = self::$anim->getAnimation();
        $this->assertEquals(19865, strlen($animData), 'Animation binary size should be int(20503)');
    }
    
    public function testSave() {
        self::$anim = new FFmpegAnimatedGif(self::$outFilePath, 100, 120, 1, 0);
        self::$anim->addFrame(self::$frame1);
        self::$anim->addFrame(self::$frame2);

        $saveResult = self::$anim->save();
        $this->assertEquals(true, $saveResult, 'Save result should be true');
        $this->assertEquals(true, file_exists(self::$outFilePath), 'File "'.self::$outFilePath.'" should exist after saving');      
        $this->assertEquals(19865, filesize(self::$outFilePath), 'Animation binary size should be int(20503)');
        $imageInfo = getimagesize(self::$outFilePath);
        $this->assertEquals(100, $imageInfo[0], 'Saved image width should be int(100)');
        $this->assertEquals(120, $imageInfo[1], 'Saved image height should be int(120)');
        unlink(self::$outFilePath);
    }
    
    public function testSerializeUnserialize() {
        self::$anim = new FFmpegAnimatedGif(self::$outFilePath, 100, 120, 1, 0);
        self::$anim->addFrame(self::$frame1); 
        self::$anim->addFrame(self::$frame2);
        
        $serialized  = serialize(self::$anim);
        self::$anim = null;
        self::$anim = unserialize($serialized);

        $saveResult = self::$anim->save();
        $this->assertEquals(true, $saveResult, 'Save result should be true');
        $this->assertEquals(true, file_exists(self::$outFilePath), 'File "'.self::$outFilePath.'" should exist after saving');      
        $this->assertEquals(19865, filesize(self::$outFilePath), 'Animation binary size should be int(20503)');
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