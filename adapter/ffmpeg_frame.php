<?php
/**
 * ffmpeg_frame serves as a compatiblity adapter for old ffmpeg-php extension
 * 
 * @author char0n (Vladimír Gorej, gorej@codescale.net)
 * @package FFmpegPHP
 * @subpackage adapter
 * @link http://ffmpeg-php.sourceforge.net/doc/api/ffmpeg_frame.php
 * @license New BSD
 * @version 2.6
 */
class ffmpeg_frame {

    protected $adaptee;
    
    public function __construct($gdImage, $pts = 0.0) {
        $this->adaptee = new FFmpegFrame($gdImage, $pts);
    }

    public function getWidth() {
        return $this->adaptee->getWidth();
    }
    
    public function getHeight() {
        return $this->adaptee->getHeight();
    }
    
    public function getPTS() {
        return $this->adaptee->getPTS();
    }

    public function getPresentationTimestamp() {
        return $this->adaptee->getPresentationTimestamp();
    }
    
    public function resize($width, $height, $cropTop = 0, $cropBottom = 0, $cropLeft = 0, $cropRight = 0) {        
        return $this->adaptee->resize($width, $height, $cropTop, $cropBottom, $cropLeft, $cropRight);
    } 
                                                  
    public function crop($cropTop, $cropBottom = 0, $cropLeft = 0, $cropRight = 0) {
        return $this->adaptee->crop($cropTop, $cropBottom, $cropLeft, $cropRight);
    }
    
    public function toGDImage() {
        return $this->adaptee->toGDImage();
    }
    
    public function __clone() {
        $this->adaptee = clone $this->adaptee;
    }
    
    public function __destruct() {
        $this->adaptee = null;
    }    
}