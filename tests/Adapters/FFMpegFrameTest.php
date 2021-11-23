<?php
namespace Char0n\FFMpegPHP\Tests\Adapters;

use PHPUnit\Framework\TestCase;
use Char0n\FFMpegPHP\Adapters\FFMpegMovie;
use Char0n\FFMpegPHP\Adapters\FFMpegFrame;

class FFMpegFrameTest extends TestCase
{

    protected static $moviePath;
    /**
     * @var FFMpegMovie
     */
    protected $movie;
    /**
     * @var FFMpegFrame
     */
    protected $frame;

    public static function setUpBeforeClass(): void
    {
        $path = dirname(__FILE__).DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'data'.DIRECTORY_SEPARATOR;
        self::$moviePath = realpath($path.'test.mp4');
    }

    public static function tearDownAfterClass(): void
    {
        self::$moviePath = null;
    }

    public function setUp(): void
    {
        $this->movie = new FFMpegMovie(self::$moviePath);
        $this->frame = $this->movie->getFrame(1);
    }

    public function tearDown(): void
    {
        $this->movie = null;
        $this->frame = null;
    }

    public function testConstructor()
    {
        $this->expectException(\UnexpectedValueException::class);
        $this->expectExceptionCode(334563);

        new FFmpegFrame('test', 0.0);
    }

    public function testFrameExtracted()
    {
        $this->assertInstanceOf(FFMpegFrame::class, $this->frame);
    }

    public function testGetWidth()
    {
        $this->assertIsInt($this->frame->getWidth(), 'Frame width is of integer type');
        $this->assertEquals(640, $this->frame->getWidth(), 'Frame width should be int(640)');
    }

    public function testGetHeight()
    {
        $this->assertIsInt($this->frame->getHeight(), 'Frame height is of integer type');
        $this->assertEquals(272, $this->frame->getHeight(), 'Frame height should be int(272)');
    }

    public function testGetPts()
    {
        $this->assertIsFloat($this->frame->getPts(), 'Pts is of integer type');
        $this->assertEquals(0.0, $this->frame->getPts(), 'Pts should be float(0.0)');
    }

    public function testGetPresentationTimestamp()
    {
        $this->assertIsFloat($this->frame->getPresentationTimestamp(), 'Presentation timestamp is of integer type');
        $this->assertEquals(
            0.0,
            $this->frame->getPresentationTimestamp(),
            'Presentation timestamp should be float(0.0)'
        );
        $this->assertEquals(
            $this->frame->getPts(),
            $this->frame->getPresentationTimestamp(),
            'Presentation timestamp should equal Pts'
        );
    }

    public function testResize()
    {
        $oldWidth  = $this->frame->getWidth();
        $oldHeight = $this->frame->getHeight();

        $this->frame->resize(300, 300);
        $this->assertIsInt($this->frame->getWidth(), 'Frame width is of integer type');
        $this->assertEquals(300, $this->frame->getWidth(), 'Frame width should be int(300)');
        $this->assertIsInt($this->frame->getHeight(), 'Frame height is of integer type');
        $this->assertEquals(300, $this->frame->getHeight(), 'Frame height should be int(300)');
        $this->frame->resize($oldWidth, $oldHeight);
        $this->assertIsInt($this->frame->getWidth(), 'Frame width is of integer type');
        $this->assertEquals(640, $this->frame->getWidth(), 'Frame width should be int(640)');
        $this->assertIsInt($this->frame->getHeight(), 'Frame height is of integer type');
        $this->assertEquals(272, $this->frame->getHeight(), 'Frame height should be int(272)');
    }

    public function testCrop()
    {
        $oldWidth  = $this->frame->getWidth();
        $oldHeight = $this->frame->getHeight();

        $this->frame->crop(100);
        $this->assertIsInt($this->frame->getWidth(), 'Frame width is of integer type');
        $this->assertEquals(640, $this->frame->getWidth(), 'Frame width should be int(300)');
        $this->assertIsInt($this->frame->getHeight(), 'Frame height is of integer type');
        $this->assertEquals(172, $this->frame->getHeight(), 'Frame height should be int(172)');
        $this->frame->resize($oldWidth, $oldHeight);
        $this->assertIsInt($this->frame->getWidth(), 'Frame width is of integer type');
        $this->assertEquals(640, $this->frame->getWidth(), 'Frame width should be int(640)');
        $this->assertIsInt($this->frame->getHeight(), 'Frame height is of integer type');
        $this->assertEquals(272, $this->frame->getHeight(), 'Frame height should be int(272)');
    }

    public function testToGdImage()
    {
        $gdImage = $this->frame->toGDImage();

        $this->assertTrue(
            is_resource($gdImage) || get_class($gdImage) === 'GdImage',
            'GdImage is of resource(gd2) type or \GdImage class'
        );
    }

    public function testSerializeUnserialize()
    {
        $serialized  = serialize($this->frame);
        $this->frame = null;
        $this->frame = unserialize($serialized);
        $this->assertIsInt($this->frame->getWidth(), 'Frame width is of integer type');
        $this->assertEquals(640, $this->frame->getWidth(), 'Frame width should be int(640)');
        $this->assertIsInt($this->frame->getHeight(), 'Frame height is of integer type');
        $this->assertEquals(272, $this->frame->getHeight(), 'Frame height should be int(272)');
    }

    public function testClone()
    {
        $gdImage = $this->frame->toGdImage();
        $cloned = clone $this->frame;

        if (is_resource($gdImage)) {
            $uoid   = (string) $gdImage;
            $cuoid  = (string) $cloned->toGdImage();
        } else {
            $uoid   = spl_object_id($gdImage);
            $cuoid  = spl_object_id($cloned->toGdImage());
        }

        $this->assertNotEquals($uoid, $cuoid);
    }
}
