<?php
/**
 * \FFmpegPHP\Adapter\ffmpeg_animated_gif serves as a compatibility Adapter for old ffmpeg-php extension.
 * 
 * @author char0n (Vladimír Gorej, gorej@codescale.net)
 * @package FFmpegPHP
 * @subpackage Adapter
 * @link http://ffmpeg-php.sourceforge.net/doc/api/ffmpeg_animated_gif.php
 * @license New BSD
 */
namespace FFmpegPHP\Adapter {

    use \FFmpegPHP\AnimatedGif;
    use \FFmpegPHP\Frame;
    use \FFmpegPHP\Adapter\ffmpeg_frame;

    class ffmpeg_animated_gif {

        protected $adaptee;

        public function __construct($outFilePath, $width, $height, $frameRate, $loopCount) {
            $this->adaptee = new AnimatedGif($outFilePath, $width, $height, $frameRate, $loopCount);
        }

        public function addFrame(ffmpeg_frame $frame) {
            $this->adaptee->addFrame(new Frame($frame->toGDImage(), $frame->getPTS()));
            return $this->adaptee->save();
        }

        public function __clone() {
            $this->adaptee = clone $this->adaptee;
        }

        public function __destruct() {
            $this->adaptee = null;
        }
    }
}