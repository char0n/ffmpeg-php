<?php

namespace Char0n\FFMpegPHP\Tests;

use PHPUnit\Framework\TestCase;
use SebastianBergmann\Timer\Timer;
use Char0n\FFMpegPHP\Movie;
use Char0n\FFMpegPHP\Frame;
use Char0n\FFMpegPHP\OutputProviders\FFMpegProvider;

class MovieTest extends TestCase
{

    protected static $moviePath;

    protected static $movieUrl;
    /**
     * @var Movie
     */
    protected $movie;

    protected static $audioPath;
    /**
     * @var Movie
     */
    protected $audio;

    protected static $noMediaPath;

    public static function setUpBeforeClass(): void
    {
        self::$moviePath   = dirname(__FILE__).DIRECTORY_SEPARATOR.'data'.DIRECTORY_SEPARATOR.'test.mp4';
        self::$audioPath   = dirname(__FILE__).DIRECTORY_SEPARATOR.'data'.DIRECTORY_SEPARATOR.'test.wav';
        self::$noMediaPath = dirname(__FILE__).DIRECTORY_SEPARATOR.'data'.DIRECTORY_SEPARATOR.'test1.txt';
        self::$movieUrl = 'https://github.com/char0n/ffmpeg-php/blob/master/tests/data/test.mp4?raw=true';
    }

    public static function tearDownAfterClass(): void
    {
        self::$moviePath   = null;
        self::$audioPath   = null;
        self::$noMediaPath = null;
    }

    public function setUp(): void
    {
        $this->movie = new Movie(self::$moviePath);
        $this->audio = new Movie(self::$audioPath);
    }

    public function tearDown(): void
    {
        $this->movie = null;
        $this->audio = null;
    }

    public function testFileDoesNotExistException()
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionCode(334561);

        new Movie(uniqid('test', true));
    }

    public function testRemoteUrlMovieFile()
    {
        $movie = new Movie(self::$movieUrl);
        $this->assertInstanceOf(Movie::class, $movie);
    }

    public function testRemoteUrlMovieFileDoesNotExistException()
    {
        $this->expectException(\UnexpectedValueException::class);
        $this->expectExceptionCode(334561);

        new Movie('https://github.com/char0n/test.mp4');
    }

    public function testPersistentResourceSimulation()
    {
        Timer::start();
        $movie = new Movie(self::$moviePath, new FFMpegProvider('ffmpeg', true));
        $movie = new Movie(self::$moviePath, new FFMpegProvider('ffmpeg', true));
        $movie = new Movie(self::$moviePath, new FFMpegProvider('ffmpeg', true));
        $elapsed = Timer::stop();

        Timer::start();
        $movie = new Movie(self::$moviePath);
        $movie = new Movie(self::$moviePath);
        $movie = new Movie(self::$moviePath);
        $elapsed1 = Timer::stop();
        $this->assertGreaterThan($elapsed, $elapsed1, 'Persistent resource simulation should be faster');
    }

    public function testGetDuration()
    {
        $this->assertIsFloat($this->movie->getDuration(), 'Duration is of float type');
        $this->assertEquals(32.14, $this->movie->getDuration(), 'Duration should be float(32.14)');
    }

    public function testGetDurationAudio()
    {
        $this->assertIsFloat($this->audio->getDuration(), 'Duration is of float type');
        $this->assertEquals(15.85, $this->audio->getDuration(), 'Duration should be float(15.85)');
    }

    public function testGetFrameCount()
    {
        $this->assertIsInt($this->movie->getFrameCount(), 'Frame count is of integer type');
        $this->assertEquals(803, $this->movie->getFrameCount(), 'Frame count should be int(830)');
    }

    public function testGetFrameRate()
    {
        $this->assertIsFloat($this->movie->getFrameRate(), 'FrameRate is of float type');
        $this->assertEquals(25, $this->movie->getFrameRate(), 'FrameRate should be float(25)');
    }

    public function testGetFileName()
    {
        $this->assertIsString($this->movie->getFilename(), 'Filename is of type string');
        $this->assertEquals(self::$moviePath, $this->movie->getFilename(), 'Filename should be string(data/test.avi)');
    }

    public function testGetComment()
    {
        $this->assertIsString($this->movie->getComment(), 'Comment is of string type');
        $this->assertEquals('test comment', $this->movie->getComment(), 'Comment should be string(test comment)');
    }

    public function testGetTitle()
    {
        $this->assertIsString($this->movie->getTitle(), 'Title is of string type');
        $this->assertEquals('title test', $this->movie->getTitle(), 'Title should be string(title test)');
    }

    public function testGetArtist()
    {
        $this->assertIsString($this->movie->getArtist(), 'Artist is of string type');
        $this->assertEquals('char0n', $this->movie->getArtist(), 'Artist should be string(char0n)');
    }

    public function testGetAuthor()
    {
        $this->assertIsString($this->movie->getAuthor(), 'Author is of string type');
        $this->assertEquals('char0n', $this->movie->getAuthor(), 'Author should be string(char0n)');
        $this->assertEquals($this->movie->getArtist(), $this->movie->getAuthor(), 'Author should qual Artist');
    }

    public function testGetCopyright()
    {
        $this->assertIsString($this->movie->getCopyright(), 'Copyright is of string type');
        $this->assertEquals(
            'test copyright',
            $this->movie->getCopyright(),
            'Copyright should be string(test copyright)'
        );
    }

    public function testGetGenre()
    {
        $this->assertIsString($this->movie->getGenre(), 'Genre is of string type');
        $this->assertEquals('test genre', $this->movie->getGenre(), 'Genre should be string(test genre)');
    }

    public function testGetTrackNumber()
    {
        $this->assertIsInt($this->movie->getTrackNumber(), 'Track number is of integer type');
        $this->assertEquals(2, $this->movie->getTrackNumber(), 'Track number should be int(2)');
    }

    public function testGetYear()
    {
        $this->assertIsInt($this->movie->getYear(), 'Year is of integer type');
        $this->assertEquals(
            true,
            $this->movie->getYear() == 2010 || $this->movie->getYear() == 0,
            'Year should be int(2010)'
        );
    }

    public function testGetFrameHeight()
    {
        $this->assertIsInt($this->movie->getFrameHeight(), 'Frame height is of integer type');
        $this->assertEquals(272, $this->movie->getFrameHeight(), 'Frame height should be int(272)');
    }

    public function testGetFrameWidth()
    {
        $this->assertIsInt($this->movie->getFrameWidth(), 'Frame width is of integer type');
        $this->assertEquals(640, $this->movie->getFrameWidth(), 'Frame width should be int(640)');
    }

    public function testGetPixelFormat()
    {
        $this->assertIsString($this->movie->getPixelFormat(), 'Pixel format is of string type');
        $this->assertEquals('yuv420p', $this->movie->getPixelFormat(), 'Pixel format should be string(yuv420p)');
    }

    public function testGetPixelAspectRatio()
    {
        $this->assertIsFloat($this->movie->getPixelAspectRatio(), 'Pixel aspect ratio is of float type');
        $this->assertEquals(
            2.35,
            round($this->movie->getPixelAspectRatio(), 2),
            'Pixel aspect ratio should be float(2.35)'
        );
    }

    public function testGetRotation()
    {
        $this->assertIsInt($this->movie->getRotation(), 'Frame rotation is of integer type');
        $this->assertEquals(0, $this->movie->getRotation(), 'Frame rotation should be int(0)');
    }

    public function testGetBitRate()
    {
        $this->assertIsInt($this->movie->getBitRate(), 'BitRate is of integer type');
        $this->assertEquals(296000, $this->movie->getBitRate(), 'BitRate should be int(296000)');
    }

    public function testGetBitRateAudio()
    {
        $this->assertIsInt($this->audio->getBitRate(), 'BitRate is of integer type');
        $this->assertEquals(178000, $this->audio->getBitRate(), 'BitRate should be int(178000)');
    }

    public function testGetVideoBitRate()
    {
        $this->assertIsInt($this->movie->getVideoBitRate(), 'Video BitRate is of integer type');
        $this->assertEquals(224000, $this->movie->getVideoBitRate(), 'Video BitRate should be int(224000)');
    }

    public function testGetAudioBitRate()
    {
        $this->assertIsInt($this->movie->getAudioBitRate(), 'Audio BitRate is of integer type');
        $this->assertEquals(67000, $this->movie->getAudioBitRate(), 'Audio BitRate should be int(67000)');
    }

    public function testGetAudioSampleRate()
    {
        $this->assertIsInt($this->movie->getAudioSampleRate(), 'Audio SampleRate is of integer type');
        $this->assertEquals(44100, $this->movie->getAudioSampleRate(), 'Audio SampleRate should be int(44100)');
    }

    public function testGetAudioSampleRateAudio()
    {
        $this->assertIsInt($this->audio->getAudioSampleRate(), 'Audio SampleRate is of integer type');
        $this->assertEquals(22050, $this->audio->getAudioSampleRate(), 'Audio SampleRate should be int(22050)');
    }

    public function testGetFrameNumber()
    {
        $this->assertIsInt($this->movie->getFrameNumber(), 'Frame number is of integer type');
        $this->assertEquals(1, $this->movie->getFrameNumber(), 'Frame number should be int(1)');

        $this->assertInstanceOf(Frame::class, $this->movie->getNextKeyFrame());
        $this->assertIsInt($this->movie->getFrameNumber(), 'Frame number is of integer type');
        $this->assertEquals(1, $this->movie->getFrameNumber(), 'Frame number should be int(1)');

        $this->assertInstanceOf(Frame::class, $this->movie->getNextKeyFrame());
        $this->assertIsInt($this->movie->getFrameNumber(), 'Frame number is of integer type');
        $this->assertEquals(2, $this->movie->getFrameNumber(), 'Frame number should be int(2)');

        $this->assertInstanceOf(Frame::class, $this->movie->getFrame());
        $this->assertIsInt($this->movie->getFrameNumber(), 'Frame number is of integer type');
        $this->assertEquals(3, $this->movie->getFrameNumber(), 'Frame number should be int(3)');
    }

    public function testGetVideoCodec()
    {
        $this->assertIsString($this->movie->getVideoCodec(), 'Video codec is of string type');
        $this->assertEquals(
            'mpeg4 (Simple Profile) (mp4v / 0x7634706D)',
            $this->movie->getVideoCodec(),
            'Video codec should be string(mpeg4)'
        );
    }

    public function testGetAudioCodec()
    {
        $this->assertIsString($this->movie->getAudioCodec(), 'Audio codec is of string type');
        $this->assertEquals(
            'aac (LC) (mp4a / 0x6134706D)',
            $this->movie->getAudioCodec(),
            'Audio codec should be string(aac)'
        );
    }

    public function testGetAudioChannels()
    {
        $this->assertIsInt($this->movie->getAudioChannels(), 'Audio channels is of integer type');
        $this->assertEquals(2, $this->movie->getAudioChannels(), 'Audio channels should be int(2)');
    }

    public function testGetAudioChannelsAudio()
    {
        $this->assertIsInt($this->audio->getAudioChannels(), 'Audio channels is of integer type');
        $this->assertEquals(2, $this->audio->getAudioChannels(), 'Audio channels should be int(2)');
    }

    public function testHasAudio()
    {
        $this->assertIsBool($this->movie->hasAudio(), 'HasAudio is of boolean type');
        $this->assertEquals(true, $this->movie->hasAudio(), 'HasAudio should be boolean(true)');
    }

    public function testHasAudioAudio()
    {
        $this->assertIsBool($this->audio->hasAudio(), 'HasAudio is of boolean type');
        $this->assertEquals(true, $this->audio->hasAudio(), 'HasAudio should be boolean(true)');
    }

    public function testHasVideo()
    {
        $this->assertIsBool($this->movie->hasVideo(), 'HasVideo is of boolean type');
        $this->assertEquals(true, $this->movie->hasVideo(), 'HasVideo is of should be boolean(true)');
    }

    public function testHasVideoAudio()
    {
        $this->assertIsBool($this->audio->hasVideo(), 'HasVideo of audio file is of boolean type');
        $this->assertEquals(false, $this->audio->hasVideo(), 'HasVideo of audio file is of should be boolean(false)');
    }

    public function testGetFrame()
    {
        $this->assertInstanceOf(Frame::class, $this->movie->getFrame(), 'Frame is of Frame type');
        $this->assertEquals(1, $this->movie->getFrameNumber(), 'Frame number should be int(1)');

        $this->assertInstanceOf(Frame::class, $this->movie->getFrame(25), 'Frame is of Frame type');

        $this->assertInstanceOf(Frame::class, $this->movie->getFrame(), 'Frame is of Frame type');
        $this->assertEquals(2, $this->movie->getFrameNumber(), 'Frame number should be int(2)');
    }

    public function testGetNextKeyFrame()
    {
        $this->assertInstanceOf(Frame::class, $this->movie->getNextKeyFrame(), 'KeyFrame is of Frame type');
        $this->assertEquals(1, $this->movie->getFrameNumber(), 'Frame number should be int(1)');
        $this->assertInstanceOf(
            Frame::class,
            $this->movie->getNextKeyFrame(),
            'Next key frame is of Frame type'
        );
        $this->assertEquals(2, $this->movie->getFrameNumber(), 'Frame number should be int(2)');
    }

    public function testSerializeUnserialize()
    {
        $serialized  = serialize($this->movie);
        $this->movie = null;
        $this->movie = unserialize($serialized);
        $this->assertIsFloat($this->movie->getDuration(), 'Duration is of float type');
        $this->assertEquals(32.14, $this->movie->getDuration(), 'Duration should be float(32.14)');
    }
}
