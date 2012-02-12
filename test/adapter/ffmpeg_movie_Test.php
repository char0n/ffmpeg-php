<?php
/**
 * Testing framework: PHPUnit (http://www.phpunit.de)
 *
 * 1.) Install phpunit on your operating system
 * 2.) Run the test
 * 
 * phpunit --bootstrap test/bootstrap.php test/adapter/ffmpeg_movie_test.php
 */
/**
 * ffmpeg_movie_test contains tests for ffmpeg_movie adapter class
 * 
 * @author char0n (Vladimír Gorej, gorej@codescale.net)
 * @category tests
 * @package FFmpegPHP
 * @subpackage adapter
 * @license New BSD
 * @version 2.6
 */
class ffmpeg_movie_test extends PHPUnit_Framework_TestCase {

    protected static $moviePath;
    protected $movie;              
    
    protected static $audioPath;
    protected $audio;
    
    protected static $noMediaPath;
    
    public static function setUpBeforeClass() {
        self::$moviePath   = realpath(dirname(__FILE__).DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'data'.DIRECTORY_SEPARATOR.'test.mp4');
        self::$audioPath   = realpath(dirname(__FILE__).DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'data'.DIRECTORY_SEPARATOR.'test.wav');
        self::$noMediaPath = realpath(dirname(__FILE__).DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'data'.DIRECTORY_SEPARATOR.'test1.txt');
    } 

    public static function tearDownAfterClass() {
        self::$moviePath   = null;
        self::$audioPath   = null;    
        self::$noMediaPath = null;            
    } 

    public function setUp() {
        $this->movie = new ffmpeg_movie(self::$moviePath);
        $this->audio = new ffmpeg_movie(self::$audioPath);
    }

    public function tearDown() {
        $this->movie = null;
        $this->audio = null;
    }
    
    public function testFileDoesNotExistException() {
        try {
            $movie = new ffmpeg_movie(uniqid('test', true));
        } catch (Exception $ex) {
            if ($ex->getCode() == 334561) {
                return;
            } else {
                $this->fail('Expected exception raised with wrong code');
            }
        }
        
        $this->fail('An expected exception with code 334561 has not been raised');
    }
    
    public function testPersistentResourceSimulation() {
        PHP_Timer::start();
        $movie   = new ffmpeg_movie(self::$moviePath, true);
        $movie   = new ffmpeg_movie(self::$moviePath, true);
        $movie   = new ffmpeg_movie(self::$moviePath, true);
        $elapsed = PHP_Timer::stop();
        
        PHP_Timer::start();
        $movie   = new ffmpeg_movie(self::$moviePath);
        $movie   = new ffmpeg_movie(self::$moviePath);
        $movie   = new ffmpeg_movie(self::$moviePath);
        $elapsed1 = PHP_Timer::stop();
        $this->assertGreaterThan($elapsed, $elapsed1, 'Persistent resource simulation should be faster');
    }
    
    public function testGetDuration() {
        $this->assertInternalType('float', $this->movie->getDuration(), 'Duration is of float type');
        $this->assertEquals(32.13, $this->movie->getDuration(), 'Duration should be float(32.13)');
    }
    
    public function testGetDuration_Audio() {
        $this->assertInternalType('float', $this->audio->getDuration(), 'Duration is of float type');
        $this->assertEquals(15.84, $this->audio->getDuration(), 'Duration should be float(15.88)');
    }
    
    public function testGetFrameCount() {
        $this->assertInternalType('int', $this->movie->getFrameCount(), 'Frame count is of integer type');
        $this->assertEquals(803, $this->movie->getFrameCount(), 'Frame count should be int(830)');
    }
    
    public function testGetFrameRate() {
        $this->assertInternalType('float', $this->movie->getFrameRate(), 'FrameRate is of float type');
        $this->assertEquals(25, $this->movie->getFrameRate(), 'FrameRate should be float(25)');
    }    
    
    public function testGetFileName() {
        $this->assertInternalType('string', $this->movie->getFilename(), 'Filename is of type string');
        $this->assertEquals(self::$moviePath, $this->movie->getFilename(), 'Filename should be string(*/test/data/test.avi)');
    }
    
    public function testGetComment() {
        $this->assertInternalType('string', $this->movie->getComment(), 'Comment is of string type');
        $this->assertEquals('test comment', $this->movie->getComment(), 'Comment should be string(test comment)');
    }
    
    public function testGetTitle() {
        $this->assertInternalType('string', $this->movie->getTitle(), 'Title is of string type');
        $this->assertEquals('title test', $this->movie->getTitle(), 'Title should be string(title test)');
    }
    
    public function testGetArtist() {
        $this->assertInternalType('string', $this->movie->getArtist(), 'Artist is of string type');
        $this->assertEquals('char0n', $this->movie->getArtist(), 'Artist should be string(char0n)');
    }
    
    public function testGetAuthor() {
        $this->assertInternalType('string', $this->movie->getAuthor(), 'Author is of string type');
        $this->assertEquals('char0n', $this->movie->getAuthor(), 'Author should be string(char0n)');    
        $this->assertEquals($this->movie->getArtist(), $this->movie->getAuthor(), 'Author should qual Artist');    
    }
    
    public function testGetCopyright() {
        $this->assertInternalType('string', $this->movie->getCopyright(), 'Copyright is of string type');
        $this->assertEquals('test copyright', $this->movie->getCopyright(), 'Copyright should be string(test copyright)');
    }    
    
    public function testGetGenre() {
        $this->assertInternalType('string', $this->movie->getGenre(), 'Genre is of string type');
        $this->assertEquals('test genre', $this->movie->getGenre(), 'Genre should be string(test genre)');
    }    
    
    public function testGetTrackNumber() {
        $this->assertInternalType('int', $this->movie->getTrackNumber(), 'Track number is of integer type');
        $this->assertEquals(2, $this->movie->getTrackNumber(), 'Track number should be int(2)');
    }    
    
    public function testGetYear() {
        $this->assertInternalType('int', $this->movie->getYear(), 'Year is of integer type');
        $this->assertTrue($this->movie->getYear() == 2010 || $this->movie->getYear() == 0, 'Year should be int(2010)');
    }    
    
    public function testGetFrameHeight() {
        $this->assertInternalType('int', $this->movie->getFrameHeight(), 'Frame height is of integer type');
        $this->assertEquals(272, $this->movie->getFrameHeight(), 'Frame height should be int(272)');
    }
    
    public function testGetFrameWidth() {
        $this->assertInternalType('int', $this->movie->getFrameWidth(), 'Frame width is of integer type');
        $this->assertEquals(640, $this->movie->getFrameWidth(), 'Frame width should be int(640)');
    }   
    
    public function testGetPixelFormat() {
        $this->assertInternalType('string', $this->movie->getPixelFormat(), 'Pixel format is of string type');
        $this->assertEquals('yuv420p', $this->movie->getPixelFormat(), 'Pixel format should be string(yuv420p)');
    }    
    
    public function testGetBitRate() {
        $this->assertInternalType('int', $this->movie->getBitRate(), 'BitRate is of integer type');
        $this->assertEquals(296000, $this->movie->getBitRate(), 'BitRate should be int(296000)');
    }            
    
    public function testGetBitRate_Audio() {
        $this->assertInternalType('int', $this->audio->getBitRate(), 'BitRate is of integer type');
        $this->assertEquals(178000, $this->audio->getBitRate(), 'BitRate should be int(178000)');
    }
    
    public function testGetVideoBitRate() {
        $this->assertInternalType('int', $this->movie->getVideoBitRate(), 'Video BitRate is of integer type');
        $this->assertEquals(224000, $this->movie->getVideoBitRate(), 'Video BitRate should be int(224000)');
    }        
    
    public function testGetAudioBitRate() {
        $this->assertInternalType('int', $this->movie->getAudioBitRate(), 'Audio BitRate is of integer type');
        $this->assertEquals(67000, $this->movie->getAudioBitRate(), 'Audio BitRate should be int(67000)');
    }  
    
    public function testGetAudioSampleRate() {
        $this->assertInternalType('int', $this->movie->getAudioSampleRate(), 'Audio SampleRate is of integer type');
        $this->assertEquals(44100, $this->movie->getAudioSampleRate(), 'Audio SampleRate should be int(44100)');
    }      
    
    public function testGetAudioSampleRate_Audio() {
        $this->assertInternalType('int', $this->audio->getAudioSampleRate(), 'Audio SampleRate is of integer type');
        $this->assertEquals(22050, $this->audio->getAudioSampleRate(), 'Audio SampleRate should be int(22050)');
    }
    
    public function testGetFrameNumber() {
        $this->assertInternalType('int', $this->movie->getFrameNumber(), 'Frame number is of integer type');
        $this->assertEquals(1, $this->movie->getFrameNumber(), 'Frame number should be int(1)');
        
        $this->assertInstanceOf('ffmpeg_frame', $this->movie->getNextKeyFrame());
        $this->assertInternalType('int', $this->movie->getFrameNumber(), 'Frame number is of integer type');
        $this->assertEquals(1, $this->movie->getFrameNumber(), 'Frame number should be int(1)');        
        
        $this->assertInstanceOf('ffmpeg_frame', $this->movie->getNextKeyFrame());
        $this->assertInternalType('int', $this->movie->getFrameNumber(), 'Frame number is of integer type');
        $this->assertEquals(2, $this->movie->getFrameNumber(), 'Frame number should be int(2)');     
        
        $this->assertInstanceOf('ffmpeg_frame', $this->movie->getFrame());
        $this->assertInternalType('int', $this->movie->getFrameNumber(), 'Frame number is of integer type');
        $this->assertEquals(3, $this->movie->getFrameNumber(), 'Frame number should be int(3)');     
    }      
    
    public function testGetVideoCodec() {
        $this->assertInternalType('string', $this->movie->getVideoCodec(), 'Video codec is of string type');
        $this->assertEquals('mpeg4 (Simple Profile) (mp4v / 0x7634706D)', $this->movie->getVideoCodec(), 'Video codec should be string(mpeg4)');
    }      

    public function testGetAudioCodec() {
        $this->assertInternalType('string', $this->movie->getAudioCodec(), 'Audio codec is of string type');
        $this->assertEquals('aac (mp4a / 0x6134706D)', $this->movie->getAudioCodec(), 'Audio codec should be string(aac)');
    }  
    
    public function testGetAudioChannels() {
        $this->assertInternalType('int', $this->movie->getAudioChannels(), 'Audio channels is of integer type');
        $this->assertEquals(2, $this->movie->getAudioChannels(), 'Audio channels should be int(2)');
    }      
    
    public function testGetAudioChannels_Audio() {
        $this->assertInternalType('int', $this->audio->getAudioChannels(), 'Audio channels is of integer type');
        $this->assertEquals(2, $this->audio->getAudioChannels(), 'Audio channels should be int(2)');        
    }
    
    public function testHasAudio() {
        $this->assertInternalType('boolean', $this->movie->hasAudio(), 'HasAudio is of boolean type');
        $this->assertEquals(true, $this->movie->hasAudio(), 'HasAudio should be boolean(true)');
    }      
    
    public function testHasAudio_Audio() {
        $this->assertInternalType('boolean', $this->audio->hasAudio(), 'HasAudio is of boolean type');
        $this->assertEquals(true, $this->audio->hasAudio(), 'HasAudio should be boolean(true)');             
    }
    
    public function testHasVideo() {
        $this->assertInternalType('boolean', $this->movie->hasVideo(), 'HasVideo is of boolean type');
        $this->assertEquals(true, $this->movie->hasVideo(), 'HasVideo is of should be boolean(true)');
    }     
    
    public function testHasVideo_Audio() {
        $this->assertInternalType('boolean', $this->audio->hasVideo(), 'HasVideo of audio file is of boolean type');
        $this->assertEquals(false, $this->audio->hasVideo(), 'HasVideo of audio file is of should be boolean(false)');    
    } 
    
    public function testGetFrame() {
        $this->assertInstanceOf('ffmpeg_frame', $this->movie->getFrame(), 'Frame is of FFmpegFrame type');
        $this->assertEquals(1, $this->movie->getFrameNumber(), 'Frame number should be int(1)');
        
        $this->assertInstanceOf('ffmpeg_frame', $this->movie->getFrame(25), 'Frame is of FFmpegFrame type');
        
        $this->assertInstanceOf('ffmpeg_frame', $this->movie->getFrame(), 'Frame is of FFmpegFrame type');
        $this->assertEquals(2, $this->movie->getFrameNumber(), 'Frame number should be int(2)');        
    }      
    
    public function testGetNextKeyFrame() {
        $this->assertInstanceOf('ffmpeg_frame', $this->movie->getNextKeyFrame(), 'Next key frame is of FFmpegFrame type');
        $this->assertEquals(1, $this->movie->getFrameNumber(), 'Frame number should be int(1)');
        $this->assertInstanceOf('ffmpeg_frame', $this->movie->getNextKeyFrame(), 'Next key frame is of FFmpegFrame type');
        $this->assertEquals(2, $this->movie->getFrameNumber(), 'Frame number should be int(2)');
    }      
    
    public function testSerializeUnserialize() {
        $serialized  = serialize($this->movie);
        $this->movie = null;
        $this->movie = unserialize($serialized);
        $this->assertInternalType('float', $this->movie->getDuration(), 'Duration is of float type');
        $this->assertEquals(32.13, $this->movie->getDuration(), 'Duration should be float(32.13)');        
    }
}