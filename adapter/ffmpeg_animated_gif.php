<?php
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