<?php
/**
 * Testing framework: PHPUnit (http://www.phpunit.de)
 *
 * 1.) Install phpunit on your operating system
 * 2.) Run the test
 * 
 * phpunit --bootstrap test/bootstrap.php test/FFmpegFrameTest.php
 */
/**
 * FFmpegFrameTest contains tests for FFmpegFrame class
 * 
 * @author char0n (Vladimír Gorej, gorej@codescale.net)
 * @category tests
 * @package FFmpegPHP
 * @license New BSD
 * @version 2.5
 */
class FFmpegFrameTest extends PHPUnit_Framework_TestCase {

    protected static $moviePath;
    protected static $movie;
    protected static $frame;
    
    public static function setUpBeforeClass() {
        self::$moviePath   = dirname(__FILE__).DIRECTORY_SEPARATOR.'data/test.mp4';
        self::$movie       = new FFmpegMovie(self::$moviePath);
        self::$frame       = self::$movie->getFrame(1);
    }
    
    public function testConstructor() {
        try {
            $frame = new FFmpegFrame('test', 0.0);
        } catch (Exception $ex) {
            if ($ex->getCode() == 334563) {
                return;
            } else {
                $this->fail('Expected exception raised with wrong code');
            }
        }
        $this->fail('An expected exception with code 334561 has not been raised');
    }
    
    public function testFrameExtracted() {
        $this->assertType('FFmpegFrame', self::$frame);
    }
    
    public function testGetWidth() {
        $this->assertType('int', self::$frame->getWidth(), 'Frame width is of integer type');
        $this->assertEquals(640, self::$frame->getWidth(), 'Frame width should be int(640)');
    }
    
    public function testGetHeight() {
        $this->assertType('int', self::$frame->getHeight(), 'Frame height is of integer type');
        $this->assertEquals(272, self::$frame->getHeight(), 'Frame height should be int(272)');
    }    
    
    public function testGetPts() {
        $this->assertType('float', self::$frame->getPts(), 'Pts is of integer type');
        $this->assertEquals(0.0, self::$frame->getPts(), 'Pts should be float(0.0)');
    }        
    
    public function testGetPresentationTimestamp() {
        $this->assertType('float', self::$frame->getPresentationTimestamp(), 'Presentation timestamp is of integer type');
        $this->assertEquals(0.0, self::$frame->getPresentationTimestamp(), 'Presentation timestamp should be float(0.0)');        
        $this->assertEquals(self::$frame->getPts(), self::$frame->getPresentationTimestamp(), 'Presentation timestamp should equal Pts');        
    }            
    
    public function testResize() {
        $oldWidth  = self::$frame->getWidth();
        $oldHeight = self::$frame->getHeight();
        
        self::$frame->resize(300, 300);
        $this->assertType('int', self::$frame->getWidth(), 'Frame width is of integer type');
        $this->assertEquals(300, self::$frame->getWidth(), 'Frame width should be int(300)');
        $this->assertType('int', self::$frame->getHeight(), 'Frame height is of integer type');
        $this->assertEquals(300, self::$frame->getHeight(), 'Frame height should be int(300)');
        self::$frame->resize($oldWidth, $oldHeight);                
        $this->assertType('int', self::$frame->getWidth(), 'Frame width is of integer type');
        $this->assertEquals(640, self::$frame->getWidth(), 'Frame width should be int(640)');
        $this->assertType('int', self::$frame->getHeight(), 'Frame height is of integer type');
        $this->assertEquals(272, self::$frame->getHeight(), 'Frame height should be int(272)');
    }
    
    public function testCrop() {
        $oldWidth  = self::$frame->getWidth();
        $oldHeight = self::$frame->getHeight();
        
        self::$frame->crop(100);
        $this->assertType('int', self::$frame->getWidth(), 'Frame width is of integer type');
        $this->assertEquals(640, self::$frame->getWidth(), 'Frame width should be int(300)');
        $this->assertType('int', self::$frame->getHeight(), 'Frame height is of integer type');
        $this->assertEquals(172, self::$frame->getHeight(), 'Frame height should be int(172)');
        self::$frame->resize($oldWidth, $oldHeight);                
        $this->assertType('int', self::$frame->getWidth(), 'Frame width is of integer type');
        $this->assertEquals(640, self::$frame->getWidth(), 'Frame width should be int(640)');
        $this->assertType('int', self::$frame->getHeight(), 'Frame height is of integer type');
        $this->assertEquals(272, self::$frame->getHeight(), 'Frame height should be int(272)');     
    }
    
    public function testToGdImage() {
        $this->assertType('resource', self::$frame->toGdImage(), 'GdImage is of resource(gd2) type');
    }
    
    public function testSerializeUnserialize() {
        $serialized  = serialize(self::$frame);
        self::$frame = null;
        self::$frame = unserialize($serialized);
        $this->assertType('int', self::$frame->getWidth(), 'Frame width is of integer type');
        $this->assertEquals(640, self::$frame->getWidth(), 'Frame width should be int(640)');
        $this->assertType('int', self::$frame->getHeight(), 'Frame height is of integer type');
        $this->assertEquals(272, self::$frame->getHeight(), 'Frame height should be int(272)');     
    }    
    
    public function testClone() {       
        $uoid   = (string) self::$frame->toGdImage();
        $cloned = clone self::$frame;
        $cuoid  = (string) $cloned->toGdImage();
        $this->assertNotEquals($uoid, $cuoid);
    }
    
    public static function tearDownAfterClass() {
        self::$moviePath = null;
        self::$movie     = null;
        self::$frame     = null;
    }    
}  
?>