<?php
class ffmpeg_movie {

    protected $adaptee;
    
    public function __construct($moviePath, $persistent = false) {
        $this->adaptee = new FFmpegMovie($moviePath, $persistent);
    }
    
    public function getDuration() {
        return $this->adaptee->getDuration();
    }
    
    public function getFrameCount() {
        return $this->adaptee->getFrameCount();
    }
    
    public function getFrameRate() {
        return $this->adaptee->getFrameRate();
    }
    
    public function getFilename() {
        return $this->adaptee->getFilename();
    }
    
    public function getComment() {
        return $this->adaptee->getComment();
    }
    
    public function getTitle() {
        return $this->adaptee->getTitle();
    }
    
    public function getArtist() {
        return $this->adaptee->getArtist();
    }
    
    public function getAuthor() {
        return $this->adaptee->getAuthor();
    }
    
    public function getCopyright() {
        return $this->adaptee->getCopyright();
    }
    
    public function getGenre() {     
        return $this->adaptee->getGenre();
    }
    
    public function getTrackNumber() {    
        return $this->adaptee->getTrackNumber();
    }
    
    public function getYear() {
        return $this->adaptee->getYear();
    }    
    
    public function getFrameHeight() {
        return $this->adaptee->getFrameHeight(); 
    }
    
    public function getFrameWidth() {
        return $this->adaptee->getFrameWidth(); 
    }
    
    public function getPixelFormat() {
        return $this->adaptee->getPixelFormat(); 
    }
    
    public function getBitRate() {
        return $this->adaptee->getBitRate(); 
    }
    
    public function getVideoBitRate() {
        return $this->adaptee->getVideoBitRate(); 
    }
    
    public function getAudioBitRate() {
        return $this->adaptee->getAudioBitRate(); 
    }
    
    public function getAudioSampleRate() {
        return $this->adaptee->getAudioSampleRate(); 
    }
    
    public function getFrameNumber() {
        return $this->adaptee->getFrameNumber(); 
    }
    
    public function getVideoCodec() {
        return $this->adaptee->getVideoCodec(); 
    }
    
    public function getAudioCodec() {
        return $this->adaptee->getAudioCodec(); 
    }
    
    public function getAudioChannels() {
        return $this->adaptee->getAudioChannels(); 
    }
    
    public function hasAudio() {
        return $this->adaptee->hasAudio(); 
    }
    
    public function hasVideo() {
        return $this->adaptee->hasVideo(); 
    }
    
    public function getFrame($framenumber = null) {
        $toReturn = null;
        $frame    = $this->adaptee->getFrame($framenumber);
        if ($frame != null) {
            $toReturn = new ffmpeg_frame($frame->toGDImage(), $frame->getPTS());
            $frame    = null;
        }                            
        
        return $toReturn;
    }
    
    public function getNextKeyFrame() {        
        return $this->adaptee->getFrameHeight(); 
    }    
    
    public function __destruct() {
        $this->adaptee = null;
    }
}
?>