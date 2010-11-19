/**
* FFmpegOutputProvider ffmpeg provider implementation
* 
* @author char0n (Vladimir Gorej)
* @package FFmpegPHP
* @subpackage provider
* @license New BSD
* @version 2.5b1
*/
<?php
class FFmpegOutputProvider extends AbstractOutputProvider {
	
    protected static $EX_CODE_NO_FFMPEG = 334560;
		
    public function __construct($ffmpegBinary = 'ffmpeg', $persistent = false) {
        parent::__construct($ffmpegBinary, $persistent);
    }
	
    public function getOutput() {
        
        // Persistent opening
        if ($this->persistent == true && array_key_exists(get_class($this).$this->binary.$this->movieFile, self::$persistentBuffer)) {
            return self::$persistentBuffer[get_class($this).$this->binary.$this->movieFile];
        }
        
        // File doesn't exist
        if (!file_exists($this->movieFile)) {
            throw new Exception('Movie file not found', self::$EX_CODE_FILE_NOT_FOUND);
        }
        
        // Get information about file from ffmpeg
        $output = array();
        
        exec($this->binary.' -i '.escapeshellarg($this->movieFile).' 2>&1', $output, $retVar);        
        $output = join(PHP_EOL, $output);
        
        // ffmpeg installed
        if (!preg_match('/FFmpeg version/', $output)) {
            throw new Exception('FFmpeg is not installed on host server', self::$EX_CODE_NO_FFMPEG);
        }
        
        // Storing persistent opening
        if ($this->persistent == true) {
            self::$persistentBuffer[get_class($this).$this->binary.$this->movieFile] = $output;            
        }   

        return $output;
    }
} 
?>