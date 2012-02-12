<?php
/**
 * FFmpegAnimatedGif represents an animated gif object
 * 
 * This class in implemented in rather un-orthodox way.
 * Reason is that ffmpeg doesn't provide satisfactory 
 * quality and compression of animated gifs.
 * 
 * Code fragments used from:  GIFEncoder Version 2.0 by László Zsidi
 * 
 * @author char0n (Vladimír Gorej, gorej@codescale.net)
 * @package FFmpegPHP
 * @license New BSD 
 * @version 2.6
 */
class FFmpegAnimatedGif implements Serializable {

    /**
    * Location in the filesystem where the animated gif will be written. 
    * 
    * @var string
    */
    protected $outFilePath;
    /**
    * Width of the animated gif. 
    * 
    * @var int
    */
    protected $width;
    /**
    * Height of the animated gif.
    * 
    * @var int
    */
    protected $height;
    /**
    * Frame rate of the animated gif in frames per second.
    * 
    * @var int
    */
    protected $frameRate;
    /**
    * Number of times to loop the animation. Put a zero here to loop forever or omit this parameter to disable looping. 
    * 
    * @var int
    */
    protected $loopCount;
    /**
    * Binary data of gif files to create animation
    * 
    * @var array
    */
    protected $frames;
    /**
    * Gif binary data of animation
    * 
    * @var string
    */
    protected $gifData;
    /**
    * Counter of first animation
    * 
    * @var mixed
    */
    protected $counter;
    
    /**
    * Create a new FFmpegAnimatedGif object 
    * 
    * @param string $outFilePath Location in the filesystem where the animated gif will be written.  
    * @param int $width Width of the animated gif.
    * @param int $height Height of the animated gif.   
    * @param int $frameRate Frame rate of the animated gif in frames per second.
    * @param int $loopCount Number of times to loop the animation. Put a zero here to loop forever or omit this parameter to disable looping.
    * @return FFmpegAnimatedGif
    */
    public function __construct($outFilePath, $width, $height, $frameRate, $loopCount) {
        $this->outFilePath = $outFilePath;
        $this->width       = $width;
        $this->height      = $height;
        $this->frameRate   = $frameRate;
        $this->loopCount   = ($loopCount < -1) ? 0 : $loopCount;
        $this->frames      = array();
        $this->counter     = -1;
    }
    
    /**
    * Add a frame to the end of the animated gif. 
    * 
    * @param FFmpegFrame $frame frame to add
    * @return void
    */
    public function addFrame(FFmpegFrame $frame) {
        $tmpFrame = clone $frame;
        $tmpFrame->resize($this->width, $this->height);
        ob_start();
        imagegif($tmpFrame->toGDImage());
        $this->frames[] = ob_get_clean();
        $tmpFrame = null;
    }
    
    /**
    * Adding header to the animation
    * 
    * @return void 
    */
    protected function addGifHeader() {
        $cmap = 0;

        if (ord($this->frames[0]{10}) & 0x80) {
            $cmap = 3 * (2 << (ord($this->frames[0]{10}) & 0x07));

            $this->gifData  = 'GIF89a';
            $this->gifData .= substr($this->frames[0], 6, 7);
            $this->gifData .= substr($this->frames[0], 13, $cmap);
            $this->gifData .= "!\377\13NETSCAPE2.0\3\1".$this->getGifWord($this->loopCount)."\0";
        }        
    }
    
    /**
    * Adding frame binary data to the animation
    * 
    * @param int $i index of frame from FFmpegAnimatedGif::frame array
    * @param int $d delay (5 seconds = 500 delay units)
    * @return void
    */
    protected function addFrameData($i, $d) {
        $DIS = 2;
        $COL = 0;
        
        $Locals_str = 13 + 3 * (2 << (ord($this->frames[$i]{10}) & 0x07));
        $Locals_end = strlen($this->frames[$i]) - $Locals_str - 1;
        $Locals_tmp = substr($this->frames[$i], $Locals_str, $Locals_end );

        $Global_len = 2 << (ord($this->frames[0]{10}) & 0x07);
        $Locals_len = 2 << (ord($this->frames[$i]{10}) & 0x07);

        $Global_rgb = substr($this->frames[0], 13, 3 * (2 << (ord($this->frames[0]{10}) & 0x07)));
        $Locals_rgb = substr($this->frames[$i], 13, 3 * (2 << (ord($this->frames[$i]{10}) & 0x07)));

        $Locals_ext = "!\xF9\x04".chr(($DIS << 2 ) + 0). chr(($d >> 0) & 0xFF).chr(($d >> 8) & 0xFF)."\x0\x0";

        if ($COL > -1 && ord($this->frames[$i]{10}) & 0x80) {
            for ($j = 0; $j < (2 << (ord($this->frames[$i]{10}) & 0x07)); $j++) {
                if (ord($Locals_rgb{3 * $j + 0}) == (($COL >> 16 ) & 0xFF) 
                    && ord($Locals_rgb{3 * $j + 1}) == (($COL >>  8 ) & 0xFF)
                    && ord($Locals_rgb{3 * $j + 2}) == (($COL >>  0 ) & 0xFF)
                   ) {
                    $Locals_ext = "!\xF9\x04".chr(($DIS << 2) + 1).chr(($d >> 0) & 0xFF).chr(($d >> 8) & 0xFF).chr($j)."\x0";
                    break;
                }
            }
        }
        switch ($Locals_tmp{0}) {
            case "!":
                $Locals_img = substr($Locals_tmp, 8, 10);
                $Locals_tmp = substr($Locals_tmp, 18, strlen($Locals_tmp) - 18);
                break;
            case ",":
                $Locals_img = substr($Locals_tmp, 0, 10);
                $Locals_tmp = substr($Locals_tmp, 10, strlen($Locals_tmp) - 10);
                break;
        }
        if (ord($this->frames[$i]{10}) & 0x80 && $this->counter > -1) {
            if ($Global_len == $Locals_len) {
                if ($this->gifBlockCompare($Global_rgb, $Locals_rgb, $Global_len)) {
                    $this->gifData .= ($Locals_ext.$Locals_img.$Locals_tmp);
                }
                else {
                    $byte  = ord($Locals_img{9});
                    $byte |= 0x80;
                    $byte &= 0xF8;
                    $byte |= (ord($this->frames[0]{10}) & 0x07);
                    $Locals_img{9} = chr ($byte);
                    $this->gifData .= ($Locals_ext.$Locals_img.$Locals_rgb.$Locals_tmp);
                }
            }
            else {
                $byte  = ord($Locals_img{9});
                $byte |= 0x80;
                $byte &= 0xF8;
                $byte |= (ord($this->frames[$i]{10}) & 0x07);
                $Locals_img{9} = chr($byte);
                $this->gifData .= ($Locals_ext.$Locals_img.$Locals_rgb.$Locals_tmp);
            }
        }
        else {
            $this->gifData .= ($Locals_ext.$Locals_img.$Locals_tmp);
        }
        $this->counter = 1;    
    }
    
    /**
    * Adding footer to the animation
    * 
    * @return void 
    */
    protected function addGifFooter() {
        $this->gifData .= ';';
    }
    
    /**
    * Gif integer wrapper
    * 
    * @param int $int
    * @return string
    */
    protected function getGifWord($int) {

        return (chr($int & 0xFF).chr(($int >> 8 ) & 0xFF));
    }
    
    /**
    * Gif compare block
    * 
    * @param string $GlobalBlock
    * @param string $LocalBlock
    * @param int $Len
    * @return int
    */
    protected function gifBlockCompare($GlobalBlock, $LocalBlock, $Len) {
        for ($i = 0; $i < $Len; $i++) {
            if    (
                    $GlobalBlock{3 * $i + 0} != $LocalBlock {3 * $i + 0} ||
                    $GlobalBlock{3 * $i + 1} != $LocalBlock {3 * $i + 1} ||
                    $GlobalBlock{3 * $i + 2} != $LocalBlock {3 * $i + 2}
                ) {
                    return (0);
            }
        }

        return (1);
    }    
    
    /**
    * Saving animated gif to remote file
    * 
    * @return boolean 
    */
    public function save() {
        // No images to proces
        if (count($this->frames) == 0) return false;
                               
        return (boolean) file_put_contents($this->outFilePath, $this->getAnimation(), LOCK_EX);
    } 
    
    /**
    * Getting animation binary data
    * 
    * @return string|boolean 
    */
    public function getAnimation() {
        // No images to proces
        if (count($this->frames) == 0) return false;
        
        // Process images as animation        
        $this->addGifHeader();           
        for ($i = 0; $i < count($this->frames); $i++) {
            $this->addFrameData($i, (1 / $this->frameRate * 100));
        } 
        $this->addGifFooter();
        
        return $this->gifData;
    }
    
    public function serialize() {
        return serialize(array(
            $this->outFilePath,
            $this->width,
            $this->height,
            $this->frameRate,
            $this->loopCount,
            $this->gifData,
            $this->frames,
            $this->counter
        ));
    }
    
    public function unserialize($serialized) {
        $data = unserialize($serialized);
        list(
            $this->outFilePath,
            $this->width,
            $this->height,
            $this->frameRate,   
            $this->loopCount,
            $this->gifData,
            $this->frames,
            $this->counter
        ) = $data;
    }
}