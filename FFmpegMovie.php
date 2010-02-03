<?php     
/**
* FFmpegMovie represents a movie file
* 
* @author char0n (Vladimir Gorej)
* @package FFmpegPHP
* @license New BSD
* @version 1.0b3
*/
class FFmpegMovie implements Serializable {

    protected statiC $EX_CODE_NO_FFMPEG      = 334560;
    protected static $EX_CODE_FILE_NOT_FOUND = 334561;
    protected static $EX_CODE_UNKNOWN_FORMAT = 334562;
    
    protected static $REGEX_NO_FFMPEG         = '/FFmpeg version/';
    protected static $REGEX_UNKNOWN_FORMAT    = '/[^:]+: Unknown format/';
    protected static $REGEX_DURATION          = '/Duration: ([0-9]{2}):([0-9]{2}):([0-9]{2})(\.([0-9]+))?/';
    protected static $REGEX_FRAME_RATE        = '/frame rate: [0-9]+\.[0-9]+ \([0-9]+\/[0-9]*\) \-> ([0-9]+\.[0-9]*)/';
    protected static $REGEX_COMMENT           = '/comment\s*:\s*(.+)/i';
    protected static $REGEX_TITLE             = '/title\s*:\s*(.+)/i';
    protected static $REGEX_ARTIST            = '/artist\s*:\s*(.+)/i';
    protected static $REGEX_COPYRIGHT         = '/copyright\s*:\s*(.+)/i';
    protected static $REGEX_GENRE             = '/genre\s*:\s*(.+)/i';
    protected static $REGEX_TRACK_NUMBER      = '/track\s*:\s*(.+)/i';
    protected static $REGEX_YEAR              = '/year\s*:\s*(.+)/i';
    protected static $REGEX_FRAME_WH          = '/Video:.+?([0-9]+)x([0-9]+)/';
    protected static $REGEX_PIXEL_FORMAT      = '/Video: [^,]+, ([^,]+)/';
    protected static $REGEX_BITRATE           = '/bitrate: ([0-9]+) kb\/s/';    
    protected static $REGEX_VIDEO_BITRATE     = '/Video:.+?([0-9]+) kb\/s/';
    protected static $REGEX_AUDIO_BITRATE     = '/Audio:.+?([0-9]+) kb\/s/';
    protected static $REGEX_AUDIO_SAMPLE_RATE = '/Audio:.+?([0-9]+) Hz/';
    protected static $REGEX_VIDEO_CODEC       = '/Video:\s([^,]+),/';
    protected static $REGEX_AUDIO_CODEC       = '/Audio:\s([^,]+),/';
    protected static $REGEX_AUDIO_CHANNELS    = '/Audio:\s[^,]+,[^,]+,([^,]+)/';
    
    /**
    * Movie file path
    * 
    * @var string
    */
    protected $movieFile;
    /**
    * ffmpeg command output
    * 
    * @var string
    */
    protected $ffmpegOut;
    
    /**
    * Movie duration in seconds
    * 
    * @var float
    */
    protected $duration;    
    /**
    * Current frame index
    * 
    * @var int
    */
    protected $frameCount;
    /**
    * Movie frame rate
    * 
    * @var float
    */
    protected $frameRate;
    /**
    * Comment ID3 field
    * 
    * @var string
    */
    protected $comment;
    /**
    * Title ID3 field
    * 
    * @var string
    */
    protected $title;
    /**
    * Author ID3 field
    * 
    * @var string
    */
    protected $artist;
    /**
    * Copyright ID3 field
    * 
    * @var string
    */
    protected $copyright;
    /**
    * Genre ID3 field
    * 
    * @var string
    */    
    protected $genre;
    /**
    * Track ID3 field
    * 
    * @var int
    */
    protected $trackNumber;
    /**
    * Year ID3 field
    * 
    * @var int
    */
    protected $year;
    /**
    * Movie frame height
    * 
    * @var int
    */
    protected $frameHeight;
    /**
    * Movie frame width
    * 
    * @var int
    */
    protected $frameWidth;
    /**
    * Movie pixel format
    * 
    * @var string
    */
    protected $pixelFormat;
    /**
    * Movie bit rate combined with audio bit rate
    * 
    * @var int
    */
    protected $bitRate;    
    /**
    * Movie video stream bit rate
    * 
    * @var int
    */
    protected $videoBitRate;   
    /**
    * Movie audio stream bit rate
    * 
    * @var int
    */
    protected $audioBitRate;
    /**
    * Audio sample rate
    * 
    * @var int
    */
    protected $audioSampleRate;
    /**
    * Current frame number
    * 
    * @var int
    */
    protected $frameNumber;
    /**
    * Movie video cocec
    * 
    * @var string 
    */
    protected $videoCodec;    
    /**
    * Movie audio coded
    * 
    * @var string
    */
    protected $audioCodec;
    /**
    * Movie audio channels
    * 
    * @var int
    */
    protected $audioChannels;
    
    /**
    * Open a video or audio file and return it as an FFmpegMovie object. 
    * 
    * @param string $moviePath full path to the movie file
    * @throws Exception
    * @return FFmpegMovie
    */
    public function __construct($moviePath) {
        $this->movieFile   = $moviePath;
        $this->frameNumber = 1;
        
        $this->getFFmpegOutput();
    }
    
    /**
    * Getting ffmpeg output from command line
    *
    * @throws Exception 
    * @return void
    */
    protected function getFFmpegOutput() {
        // File doesn't exist
        if (!file_exists($this->movieFile)) {
            throw new Exception('Movie file not found', self::$EX_CODE_FILE_NOT_FOUND);
        }
        
        // Get information about file from ffmpeg
        $output = array();
        exec('ffmpeg -i '.$this->movieFile.' 2>&1', $output, $retVar);        
        $this->ffmpegOut = join(PHP_EOL, $output);
        
        // No ffmpeg installed
        if (!preg_match(self::$REGEX_NO_FFMPEG, $this->ffmpegOut)) {
            throw new Exception('No ffmpeg installed on host server', self::$EX_CODE_NO_FFMPEG);
        }
        
        // File is not video file
        if (preg_match(self::$REGEX_UNKNOWN_FORMAT, $this->ffmpegOut)) {
            throw new Exception('Unknown movie format', self::$EX_CODE_UNKNOWN_FORMAT);
        }
    }
    
    /**
    * Return the duration of a movie or audio file in seconds.
    * 
    * @return int movie duration in seconds
    */
    public function getDuration() {
        if ($this->duration === null) {
            $match = array();
            preg_match(self::$REGEX_DURATION, $this->ffmpegOut, $match);
            if (array_key_exists(1, $match) && array_key_exists(2, $match) && array_key_exists(3, $match)) {                
                $hours     = (int)    $match[1];
                $minutes   = (int)    $match[2];
                $seconds   = (int)    $match[3];                        
                $fractions = (float)  ((array_key_exists(5, $match)) ? "0.$match[5]" : 0.0);
                
                $this->duration = (($hours * (3600)) + ($minutes * 60) + $seconds + $fractions);        
            } else {
                $this->duration = 0.0;
            }                        
            
            return $this->duration;
        }
        
        return $this->duration;        
    }
    
    /**
    * Return the number of frames in a movie or audio file.
    * 
    * @return int
    */
    public function getFrameCount() {
        if ($this->frameCount === null) {
            $this->frameCount = (int) ($this->getDuration() * $this->getFrameRate());            
        }
        
        return $this->frameCount;
    }
    
    /**
    * Return the frame rate of a movie in fps.
    *
    * @return float 
    */
    public function getFrameRate() {
        if ($this->frameRate === null) {
            $match = array();
            preg_match(self::$REGEX_FRAME_RATE, $this->ffmpegOut, $match);
            $this->frameRate = (float) ((array_key_exists(1, $match)) ? $match[1] : 0.0);
        }
        
        return $this->frameRate;
    }
    
    /**
    * Return the path and name of the movie file or audio file.
    *
    * @return string 
    */
    public function getFilename() {
        return $this->movieFile;
    }
    
    /**
    * Return the comment field from the movie or audio file.
    *
    * @return string 
    */
    public function getComment() {
        if ($this->comment === null) {
             $match = array();
             preg_match(self::$REGEX_COMMENT, $this->ffmpegOut, $match);
             $this->comment = (array_key_exists(1, $match)) ? trim($match[1]) : '';
        }
        
        return $this->comment;
    }
    
    /**
    * Return the title field from the movie or audio file.
    *
    * @return string 
    */
    public function getTitle() {
        if ($this->title === null) {
            $match = array();
            preg_match(self::$REGEX_TITLE, $this->ffmpegOut, $match);
            $this->title = (array_key_exists(1, $match)) ? trim($match[1]) : '';
        }
        
        return $this->title;
    }
    
    /**
    * Return the author field from the movie or the artist ID3 field from an mp3 file; alias $movie->getArtist()
    * 
    * @return string
    */
    public function getArtist() {
        if ($this->artist === null) {
            $match = array();
            preg_match(self::$REGEX_ARTIST, $this->ffmpegOut, $match);
            $this->artist = (array_key_exists(1, $match)) ? trim($match[1]) : '';
        }
        
        return $this->artist;
    }
    
    /**
    * Return the author field from the movie or the artist ID3 field from an mp3 file.
    * 
    * @return string
    */
    public function getAuthor() {
        return $this->getArtist();
    }
    
    /**
    * Return the copyright field from the movie or audio file.
    *
    * @return string 
    */
    public function getCopyright() {
        if ($this->copyright === null) {
            $match = array();
            preg_match(self::$REGEX_COPYRIGHT, $this->ffmpegOut, $match);
            $this->copyright = (array_key_exists(1, $match)) ? trim($match[1]) : '';
        }
        
        return $this->copyright;
    }
    
    /**
    * Return the genre ID3 field from an mp3 file.
    *
    * @return string 
    */
    public function getGenre() {
        if ($this->genre === null) {
            $match = array();
            preg_match(self::$REGEX_GENRE, $this->ffmpegOut, $match);
            $this->genre = (array_key_exists(1, $match)) ? trim($match[1]) : '';
        }
        
        return $this->genre;
    }
    
    /**
    * Return the track ID3 field from an mp3 file.
    * 
    * @return int
    */
    public function getTrackNumber() {
        if ($this->trackNumber === null) {
            $match = array();
            preg_match(self::$REGEX_TRACK_NUMBER, $this->ffmpegOut, $match);
            $this->trackNumber = (int) ((array_key_exists(1, $match)) ? $match[1] : 0);
        }
        
        return $this->trackNumber;    
    }
    
    /**
    * Return the year ID3 field from an mp3 file.
    * 
    * @return int
    */
    public function getYear() {
        if ($this->year === null) {
            $match = array();
            preg_match(self::$REGEX_YEAR, $this->ffmpegOut, $match);
            $this->year = (int) ((array_key_exists(1, $match)) ? $match[1] : 0);
        }
        
        return $this->year;    
    }    
    
    /**
    * Return the height of the movie in pixels.
    * 
    * @return int
    */
    public function getFrameHeight() {
        if ($this->frameHeight == null) {
            $match = array();
            preg_match(self::$REGEX_FRAME_WH, $this->ffmpegOut, $match);
            if (array_key_exists(1, $match) && array_key_exists(2, $match)) {
                $this->frameWidth  = (int) $match[1];
                $this->frameHeight = (int) $match[2];
            } else {
                $this->frameWidth  = 0;
                $this->frameHeight = 0;
            }
        }
        
        return $this->frameHeight;
    }
    
    /**
    * Return the width of the movie in pixels.
    *
    * @return int 
    */
    public function getFrameWidth() {
        if ($this->frameWidth == null) {
            $this->getFrameHeight();
        }
        
        return $this->frameWidth;
    }
    
    /**
    * Return the pixel format of the movie.
    *
    * @return string 
    */
    public function getPixelFormat() {
        if ($this->pixelFormat == null) {
            $match = array();
            preg_match(self::$REGEX_PIXEL_FORMAT, $this->ffmpegOut, $match);
            $this->pixelFormat = (array_key_exists(1, $match)) ? trim($match[1]) : '';
        }
        
        return $this->pixelFormat;
    }
    
    /**
    * Return the bit rate of the movie or audio file in bits per second.
    * 
    * @return int
    */
    public function getBitRate() {
        if ($this->bitRate == null) {
            $match = array();
            preg_match(self::$REGEX_BITRATE, $this->ffmpegOut, $match);
            $this->bitRate = (int) ((array_key_exists(1, $match)) ? ($match[1] * 1000) : 0);
        }
        
        return $this->bitRate;    
    }
    
    /**
    * Return the bit rate of the video in bits per second.
    *  
    * NOTE: This only works for files with constant bit rate.
    *
    * @return int 
    */
    public function getVideoBitRate() {
        if ($this->videoBitRate === null) {
            $match = array();
            preg_match(self::$REGEX_VIDEO_BITRATE, $this->ffmpegOut, $match);
            $this->videoBitRate = (int) ((array_key_exists(1, $match)) ? ($match[1] * 1000) : 0);
        }
        
        return $this->videoBitRate;
    }
    
    /**
    * Return the audio bit rate of the media file in bits per second.
    *
    * @return int 
    */
    public function getAudioBitRate() {
        if ($this->audioBitRate === null) {
            $match = array();
            preg_match(self::$REGEX_AUDIO_BITRATE, $this->ffmpegOut, $match);
            $this->audioBitRate = (int) ((array_key_exists(1, $match)) ? ($match[1] * 1000) : 0);
        }
        
        return $this->audioBitRate;
    }
    
    /**
    * Return the audio sample rate of the media file in bits per second.
    *
    * @return int 
    */
    public function getAudioSampleRate() {
        if ($this->audioSampleRate === null) {
            $match = array();
            preg_match(self::$REGEX_AUDIO_SAMPLE_RATE, $this->ffmpegOut, $match);
            $this->audioSampleRate = (int) ((array_key_exists(1, $match)) ? $match[1] : 0);
        }
        
        return $this->audioSampleRate;
    }
    
    /**
    * Return the current frame index.
    *
    * @return int 
    */
    public function getFrameNumber() {
        return $this->frameNumber;
    }
    
    /**
    * Return the name of the video codec used to encode this movie as a string.
    * 
    * @return string 
    */
    public function getVideoCodec() {
        if ($this->videoCodec === null) {
            $match = array();
            preg_match(self::$REGEX_VIDEO_CODEC, $this->ffmpegOut, $match);
            $this->videoCodec = (array_key_exists(1, $match)) ? trim($match[1]) : '';
        }
        
        return $this->videoCodec;
    }
    
    /**
    * Return the name of the audio codec used to encode this movie as a string.
    *
    * @return string 
    */
    public function getAudioCodec() {
        if ($this->audioCodec === null) {
            $match = array();
            preg_match(self::$REGEX_AUDIO_CODEC, $this->ffmpegOut, $match);
            $this->audioCodec = (array_key_exists(1, $match)) ? trim($match[1]) : '';
        }
        
        return $this->audioCodec;
    }
    
    /**
    * Return the number of audio channels in this movie as an integer.
    * 
    * @return int
    */
    public function getAudioChannels() {
        if ($this->audioChannels === null) {
            $match = array();
            preg_match(self::$REGEX_AUDIO_CHANNELS, $this->ffmpegOut, $match);
            if (array_key_exists(1, $match)) {
                switch ($match[1]) {
                    case 'mono':
                        $this->audioChannels = 1; break;
                    case 'stereo':
                        $this->audioChannels = 2; break;
                    case '5.1':
                        $this->audioChannels = 6; break;
                    case '5:1':
                        $this->audioChannels = 6; break;
                    default: 
                        $this->audioChannels = 0;
                }                 
            } else {
                $this->audioChannels = 0;
            }
        }
        
        return $this->audioChannels;
    }
    
    /**
    * Return boolean value indicating whether the movie has an audio stream.
    *
    * @return boolean 
    */
    public function hasAudio() {
        return (boolean) $this->getAudioChannels();
    }
    
    /**
    * Return boolean value indicating whether the movie has a video stream.
    *
    * @return boolean 
    */
    public function hasVideo() {
        return ($this->getVideoBitRate() === null) ? false : true;
    }
    
    /**
    * Returns a frame from the movie as an FFmpegFrame object. Returns false if the frame was not found.
    *
    *   * framenumber - Frame from the movie to return. If no framenumber is specified, returns the next frame of the movie. 
    * 
    * @param int $framenumber
    * @return FFmpegFrame|boolean
    */
    public function getFrame($framenumber = null) {
        $framePos = ($framenumber === null) ? $this->frameNumber : $framenumber;    
        
        // Frame position out of range
        if (!is_numeric($framePos) || $framePos < 0 || $framePos > $this->getFrameCount()) {
            return false;
        }        
             
        $frameFilePath = sys_get_temp_dir().uuid().'.jpg';
        $frameTime     = round((($framePos / $this->getFrameCount()) * $this->getDuration()), 4);
        exec('ffmpeg -i '.$this->movieFile.' -vframes 1 -ss '.$frameTime.' '.$frameFilePath.' 2>&1', $out);
        
        // Cannot write frame to the data storage
        if (!file_exists($frameFilePath)) {
            return false;
        }
        
        $gdImage = imagecreatefromjpeg($frameFilePath);
        if (is_writable($frameFilePath)) unlink($frameFilePath);        
        
        return new FFmpegFrame($gdImage, $frameTime);
    }
    
    /**
    * Returns the next key frame from the movie as an FFmpegFrame object. Returns false if the frame was not found. 
    * 
    * @return FFmpegFrame|boolean 
    */
    public function getNextKeyFrame() {
        return $this->getFrame($this->frameNumber++);
    }
    
    public function serialize() {
        $data = serialize(array(
            $this->movieFile,
            $this->ffmpegOut,
            $this->frameNumber
        ));
        
        return $data;
    }
    
    public function unserialize($serialized) {
        list($this->movieFile, $this->ffmpegOut, $this->frameNumber) = unserialize($serialized);
        
    }
    
    public function __destruct() {
        $this->movieFile       = null;
        $this->ffmpegOut       = null;
    
        $this->duration        = null;
        $this->frameCount      = null;
        $this->frameRate       = null;
        $this->comment         = null;
        $this->title           = null;
        $this->artist          = null;
        $this->copyright       = null;
        $this->genre           = null;
        $this->trackNumber     = null;
        $this->year            = null;
        $this->frameHeight     = null;
        $this->frameWidth      = null;
        $this->pixelFormat     = null;
        $this->bitRate         = null;
        $this->videoBitRate    = null;
        $this->audioBitRate    = null;
        $this->audioSampleRate = null;
        $this->frameNumber     = null;
        $this->videoCodec      = null;
        $this->audioCodec      = null;
        $this->audioChannels   = null;
    }
}  
?>