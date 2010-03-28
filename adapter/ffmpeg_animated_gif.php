<?php
/**
* ffmpeg_animated_gif serves as a compatiblity adapter for old ffmpeg-php extension
* 
* @author char0n (Vladimir Gorej)
* @package FFmpegPHP
* @subpackage adapter
* @link http://ffmpeg-php.sourceforge.net/doc/api/ffmpeg_animated_gif.php
* @license New BSD
* @version 1.5-b1
*/
class ffmpeg_animated_gif {

    protected $adaptee;
    
    public function __construct($outFilePath, $width, $height, $frameRate, $loopCount) {
        $this->adaptee = new FFmpegAnimatedGif($outFilePath, $width, $height, $frameRate, $loopCount);
    }
    
    public function addFrame(ffmpeg_frame $frame) {
        $this->adaptee->addFrame(new FFmpegFrame($frame->toGDImage(), $frame->getPTS()));
        return $this->adaptee->save();
    }
    
    public function __clone() {
        $this->adaptee = clone $this->adaptee;
    }
    
    public function __destruct() {
        $this->adaptee = null;
    }
}
?>