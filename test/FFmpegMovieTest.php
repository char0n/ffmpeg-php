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
* directory as this file(FFmpegMovieTest.php) type:
* 
* php ../phpunit/phpunit.php FFmpegMovieTest.php
*/

class FFmpegMovieTest extends PHPUnit_Framework_TestCase {

    protected static $movie;
    protected static $audio;
    
    public static function setUpBeforeClass() {
        self::$movie = new FFmpegMovie('data/test.mp4');
        self::$audio = new FFmpegMovie('data/test.wav');
    }    
    
    public function testFileDoesNotExistException() {
        try {
            $movie = new FFmpegMovie(uniqid('test', true));
        } catch (Exception $ex) {
            if ($ex->getCode() == 334561) {
                return;
            } else {
                $this->fail('Expected exception raised with wrong code');
            }
        }
        
        $this->fail('An expected exception with code 334561 has not been raised');
    }
    
    public function testFileIsNotVideoFileException() {
        try {
            $movie = new FFmpegMovie('data/test1.txt');            
        } catch (Exception $ex) {
            if ($ex->getCode() == 334562) {
                return;
            } else {
                $this->fail('Expected exception raised with wrong code');
            }
        }
        
        $this->fail('Expected exception with code 334562 has not been raised');
    }
    
    public function testPersistentResourceSimulation() {
        PHPUnit_Util_Timer::start();
        $movie   = new FFmpegMovie('data/test.mp4', true);
        $movie   = new FFmpegMovie('data/test.mp4', true);
        $movie   = new FFmpegMovie('data/test.mp4', true);
        $elapsed = PHPUnit_Util_Timer::stop();
        
        PHPUnit_Util_Timer::start();
        $movie   = new FFmpegMovie('data/test.mp4');
        $movie   = new FFmpegMovie('data/test.mp4');
        $movie   = new FFmpegMovie('data/test.mp4');        
        $elapsed1 = PHPUnit_Util_Timer::stop();
        $this->assertGreaterThan($elapsed, $elapsed1, 'Persistent resource simulation should be faster');
    }
    
    public function testGetDuration() {
        $this->assertType('float', self::$movie->getDuration(), 'Duration is of float type');
        $this->assertEquals(32.13, self::$movie->getDuration(), 'Duration should be float(32.13)');
    }
    
    public function testGetDuration_Audio() {
        $this->assertType('float', self::$audio->getDuration(), 'Duration is of float type');
        $this->assertEquals(15.88, self::$audio->getDuration(), 'Duration should be float(15.88)');
    }
    
    public function testGetFrameCount() {
        $this->assertType('int', self::$movie->getFrameCount(), 'Frame count is of integer type');
        $this->assertEquals(803, self::$movie->getFrameCount(), 'Frame count should be int(830)');
    }
    
    public function testGetFrameRate() {
        $this->assertType('float', self::$movie->getFrameRate(), 'FrameRate is of float type');
        $this->assertEquals(25, self::$movie->getFrameRate(), 'FrameRate should be float(25)');
    }    
    
    public function testGetFileName() {
        $this->assertType('string', self::$movie->getFilename(), 'Filename is of type string');
        $this->assertEquals('data/test.mp4', self::$movie->getFilename(), 'Filename should be string(data/test.avi)');
    }
    
    public function testGetComment() {
        $this->assertType('string', self::$movie->getComment(), 'Comment is of string type');
        $this->assertEquals('test comment', self::$movie->getComment(), 'Comment should be string(test comment)');
    }
    
    public function testGetTitle() {
        $this->assertType('string', self::$movie->getTitle(), 'Title is of string type');
        $this->assertEquals('title test', self::$movie->getTitle(), 'Title should be string(title test)');
    }
    
    public function testGetArtist() {
        $this->assertType('string', self::$movie->getArtist(), 'Artist is of string type');
        $this->assertEquals('char0n', self::$movie->getArtist(), 'Artist should be string(char0n)');
    }
    
    public function testGetAuthor() {
        $this->assertType('string', self::$movie->getAuthor(), 'Author is of string type');
        $this->assertEquals('char0n', self::$movie->getAuthor(), 'Author should be string(char0n)');    
        $this->assertEquals(self::$movie->getArtist(), self::$movie->getAuthor(), 'Author should qual Artist');    
    }
    
    public function testGetCopyright() {
        $this->assertType('string', self::$movie->getCopyright(), 'Copyright is of string type');
        $this->assertEquals('test copyright', self::$movie->getCopyright(), 'Copyright should be string(test copyright)');
    }    
    
    public function testGetGenre() {
        $this->assertType('string', self::$movie->getGenre(), 'Genre is of string type');
        $this->assertEquals('test genre', self::$movie->getGenre(), 'Genre should be string(test genre)');
    }    
    
    public function testGetTrackNumber() {
        $this->assertType('int', self::$movie->getTrackNumber(), 'Track number is of integer type');
        $this->assertEquals(2, self::$movie->getTrackNumber(), 'Track number should be int(2)');
    }    
    
    public function testGetYear() {
        $this->assertType('int', self::$movie->getYear(), 'Year is of integer type');
        $this->assertEquals(2010, self::$movie->getYear(), 'Year should be int(2010)');
    }    
    
    public function testGetFrameHeight() {
        $this->assertType('int', self::$movie->getFrameHeight(), 'Frame height is of integer type');
        $this->assertEquals(272, self::$movie->getFrameHeight(), 'Frame height should be int(272)');
    }
    
    public function testGetFrameWidth() {
        $this->assertType('int', self::$movie->getFrameWidth(), 'Frame width is of integer type');
        $this->assertEquals(640, self::$movie->getFrameWidth(), 'Frame width should be int(640)');
    }   
    
    public function testGetPixelFormat() {
        $this->assertType('string', self::$movie->getPixelFormat(), 'Pixel format is of string type');
        $this->assertEquals('yuv420p', self::$movie->getPixelFormat(), 'Pixel format should be string(yuv420p)');
    }    
    
    public function testGetBitRate() {
        $this->assertType('int', self::$movie->getBitRate(), 'BitRate is of integer type');
        $this->assertEquals(296000, self::$movie->getBitRate(), 'BitRate should be int(296000)');
    }            
    
    public function testGetBitRate_Audio() {
        $this->assertType('int', self::$audio->getBitRate(), 'BitRate is of integer type');
        $this->assertEquals(178000, self::$audio->getBitRate(), 'BitRate should be int(178000)');
    }
    
    public function testGetVideoBitRate() {
        $this->assertType('int', self::$movie->getVideoBitRate(), 'Video BitRate is of integer type');
        $this->assertEquals(224000, self::$movie->getVideoBitRate(), 'Video BitRate should be int(224000)');
    }        
    
    public function testGetAudioBitRate() {
        $this->assertType('int', self::$movie->getAudioBitRate(), 'Audio BitRate is of integer type');
        $this->assertEquals(67000, self::$movie->getAudioBitRate(), 'Audio BitRate should be int(67000)');
    }  
    
    public function testGetAudioSampleRate() {
        $this->assertType('int', self::$movie->getAudioSampleRate(), 'Audio SampleRate is of integer type');
        $this->assertEquals(44100, self::$movie->getAudioSampleRate(), 'Audio SampleRate should be int(44100)');
    }      
    
    public function testGetAudioSampleRate_Audio() {
        $this->assertType('int', self::$audio->getAudioSampleRate(), 'Audio SampleRate is of integer type');
        $this->assertEquals(22050, self::$audio->getAudioSampleRate(), 'Audio SampleRate should be int(22050)');
    }
    
    public function testGetFrameNumber() {
        $this->assertType('int', self::$movie->getFrameNumber(), 'Frame number is of integer type');
        $this->assertEquals(1, self::$movie->getFrameNumber(), 'Frame number should be int(1)');
        
        $this->assertType('FFmpegFrame', self::$movie->getNextKeyFrame());
        $this->assertType('int', self::$movie->getFrameNumber(), 'Frame number is of integer type');
        $this->assertEquals(1, self::$movie->getFrameNumber(), 'Frame number should be int(1)');        
        
        $this->assertType('FFmpegFrame', self::$movie->getNextKeyFrame());
        $this->assertType('int', self::$movie->getFrameNumber(), 'Frame number is of integer type');
        $this->assertEquals(2, self::$movie->getFrameNumber(), 'Frame number should be int(2)');                
    }      
    
    public function testGetVideoCodec() {
        $this->assertType('string', self::$movie->getVideoCodec(), 'Video codec is of string type');
        $this->assertEquals('mpeg4', self::$movie->getVideoCodec(), 'Video codec should be string(mpeg4)');
    }      

    public function testGetAudioCodec() {
        $this->assertType('string', self::$movie->getAudioCodec(), 'Audio codec is of string type');
        $this->assertEquals('aac', self::$movie->getAudioCodec(), 'Audio codec should be string(aac)');
    }  
    
    public function testGetAudioChannels() {
        $this->assertType('int', self::$movie->getAudioChannels(), 'Audio channels is of integer type');
        $this->assertEquals(2, self::$movie->getAudioChannels(), 'Audio channels should be int(2)');
    }      
    
    public function testGetAudioChannels_Audio() {
        $this->assertType('int', self::$audio->getAudioChannels(), 'Audio channels is of integer type');
        $this->assertEquals(2, self::$audio->getAudioChannels(), 'Audio channels should be int(2)');        
    }
    
    public function testHasAudio() {
        $this->assertType('boolean', self::$movie->hasAudio(), 'HasAudio is of boolean type');
        $this->assertEquals(true, self::$movie->hasAudio(), 'HasAudio should be boolean(true)');
    }      
    
    public function testHasAudio_Audio() {
        $this->assertType('boolean', self::$audio->hasAudio(), 'HasAudio is of boolean type');
        $this->assertEquals(true, self::$audio->hasAudio(), 'HasAudio should be boolean(true)');             
    }
    
    public function testHasVideo() {
        $this->assertType('boolean', self::$movie->hasVideo(), 'HasVideo is of boolean type');
        $this->assertEquals(true, self::$movie->hasVideo(), 'HasVideo is of should be boolean(true)');
    }      
    
    public function testGetFrame() {
        $this->assertType('FFmpegFrame', self::$movie->getFrame(), 'Frame is of FFmpegFrame type');
    }      
    
    public function testGetFrame1() {
        $this->assertType('FFmpegFrame', self::$movie->getFrame(1), 'Frame is of FFmpegFrame type');
    }      
    
    public function testGetNextKeyFrame() {
        $this->assertType('FFmpegFrame', self::$movie->getNextKeyFrame(), 'Next key frame is of FFmpegFrame type');
        $this->assertEquals(3, self::$movie->getFrameNumber(), 'Frame number should be int(3)');
    }      
    
    public function testSerializeUnserialize() {
        $serialized  = serialize(self::$movie);
        self::$movie = null;
        self::$movie = unserialize($serialized);
        $this->assertType('float', self::$movie->getDuration(), 'Duration is of float type');
        $this->assertEquals(32.13, self::$movie->getDuration(), 'Duration should be float(32.13)');        
    }
    
    public static function tearDownAfterClass() {
        self::$movie = null;
        self::$audio = null;
    }    
}  
?>