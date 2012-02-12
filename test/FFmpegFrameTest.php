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
 * @version 2.6
 */
class FFmpegFrameTest extends PHPUnit_Framework_TestCase {

    protected static $moviePath;
    protected $movie;
    protected $frame;
    
    public static function setUpBeforeClass() {
        self::$moviePath = dirname(__FILE__).DIRECTORY_SEPARATOR.'data'.DIRECTORY_SEPARATOR.'test.mp4';
    }

    public static function tearDownAfterClass() {
        self::$moviePath = null;
    }

    public function setUp() {
        $this->movie = new FFmpegMovie(self::$moviePath);
        $this->frame = $this->movie->getFrame(1);
    }       

    public function tearDown() {
        $this->movie = null;
        $this->frame = null;
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
        $this->assertInstanceOf('FFmpegFrame', $this->frame);
    }
    
    public function testGetWidth() {
        $this->assertInternalType('int', $this->frame->getWidth(), 'Frame width is of integer type');
        $this->assertEquals(640, $this->frame->getWidth(), 'Frame width should be int(640)');
    }
    
    public function testGetHeight() {
        $this->assertInternalType('int', $this->frame->getHeight(), 'Frame height is of integer type');
        $this->assertEquals(272, $this->frame->getHeight(), 'Frame height should be int(272)');
    }    
    
    public function testGetPts() {
        $this->assertInternalType('float', $this->frame->getPts(), 'Pts is of integer type');
        $this->assertEquals(0.0, $this->frame->getPts(), 'Pts should be float(0.0)');
    }        
    
    public function testGetPresentationTimestamp() {
        $this->assertInternalType('float', $this->frame->getPresentationTimestamp(), 'Presentation timestamp is of integer type');
        $this->assertEquals(0.0, $this->frame->getPresentationTimestamp(), 'Presentation timestamp should be float(0.0)');        
        $this->assertEquals($this->frame->getPts(), $this->frame->getPresentationTimestamp(), 'Presentation timestamp should equal Pts');        
    }            
    
    public function testResize() {
        $oldWidth  = $this->frame->getWidth();
        $oldHeight = $this->frame->getHeight();
        
        $this->frame->resize(300, 300);
        $this->assertInternalType('int', $this->frame->getWidth(), 'Frame width is of integer type');
        $this->assertEquals(300, $this->frame->getWidth(), 'Frame width should be int(300)');
        $this->assertInternalType('int', $this->frame->getHeight(), 'Frame height is of integer type');
        $this->assertEquals(300, $this->frame->getHeight(), 'Frame height should be int(300)');
        $this->frame->resize($oldWidth, $oldHeight);                
        $this->assertInternalType('int', $this->frame->getWidth(), 'Frame width is of integer type');
        $this->assertEquals(640, $this->frame->getWidth(), 'Frame width should be int(640)');
        $this->assertInternalType('int', $this->frame->getHeight(), 'Frame height is of integer type');
        $this->assertEquals(272, $this->frame->getHeight(), 'Frame height should be int(272)');
    }
    
    public function testCrop() {
        $oldWidth  = $this->frame->getWidth();
        $oldHeight = $this->frame->getHeight();
        
        $this->frame->crop(100);
        $this->assertInternalType('int', $this->frame->getWidth(), 'Frame width is of integer type');
        $this->assertEquals(640, $this->frame->getWidth(), 'Frame width should be int(300)');
        $this->assertInternalType('int', $this->frame->getHeight(), 'Frame height is of integer type');
        $this->assertEquals(172, $this->frame->getHeight(), 'Frame height should be int(172)');
        $this->frame->resize($oldWidth, $oldHeight);                
        $this->assertInternalType('int', $this->frame->getWidth(), 'Frame width is of integer type');
        $this->assertEquals(640, $this->frame->getWidth(), 'Frame width should be int(640)');
        $this->assertInternalType('int', $this->frame->getHeight(), 'Frame height is of integer type');
        $this->assertEquals(272, $this->frame->getHeight(), 'Frame height should be int(272)');     
    }
    
    public function testToGdImage() {
        $this->assertInternalType('resource', $this->frame->toGdImage(), 'GdImage is of resource(gd2) type');
    }
    
    public function testSerializeUnserialize() {
        $serialized  = serialize($this->frame);
        $this->frame = null;
        $this->frame = unserialize($serialized);
        $this->assertInternalType('int', $this->frame->getWidth(), 'Frame width is of integer type');
        $this->assertEquals(640, $this->frame->getWidth(), 'Frame width should be int(640)');
        $this->assertInternalType('int', $this->frame->getHeight(), 'Frame height is of integer type');
        $this->assertEquals(272, $this->frame->getHeight(), 'Frame height should be int(272)');     
    }    
    
    public function testClone() {       
        $uoid   = (string) $this->frame->toGdImage();
        $cloned = clone $this->frame;
        $cuoid  = (string) $cloned->toGdImage();
        $this->assertNotEquals($uoid, $cuoid);
    }
}