<?php
/**
* FFprobeOutputProvider ffprobe provider implementation
* 
* @author char0n (Vladimir Gorej)
* @package FFmpegPHP
* @subpackage provider
* @license New BSD
* @version 2.5b1
*/
class FFprobeOutputProvider extends AbstractOutputProvider {

    protected static $EX_CODE_NO_FFPROBE = 334563;    
    
    public function __construct($ffprobeBinary, $persistent = false) {
        parent::__construct($ffprobeBinary, $persistent);
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

        // Get information about file from ffprobe
        $output = array();

        exec($this->binary.' '.escapeshellarg($this->movieFile).' 2>&1', $output, $retVar);
        $output = join(PHP_EOL, $output);
                
        // ffprobe installed
        if (!preg_match('/FFprobe version/', $output)) {
            throw new Exception('FFprobe is not installed on host server', self::$EX_CODE_NO_FFPROBE);
        }

        // Storing persistent opening
        if ($this->persistent == true) {
            self::$persistentBuffer[get_class($this).$this->binary.$this->movieFile] = $output;
        }  

        return $output;
    }
} 
?>