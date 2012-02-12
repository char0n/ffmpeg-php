<?php
/**
 * StringOutputProvider ffmpeg provider implementation
 * 
 * @author funrob (Rob Walch, rwalch@gmail.com)
 * @package FFmpegPHP
 * @subpackage provider
 * @license New BSD
 * @version 2.6
 */
class StringOutputProvider extends AbstractOutputProvider {
	
    protected $_output;

    /**
     * Constructor
     * 
     * @param string $ffmpegBinary path to ffmpeg executable
     * @param boolean $persistent persistent functionality on/off
     */
    public function __construct($ffmpegBinary = 'ffmpeg', $persistent = false) {
        $this->_output = '';
        parent::__construct($ffmpegBinary, $persistent);
    }
	
    /**
     * Getting parsable output from ffmpeg binary
     * 
     * @return string
     */    
    public function getOutput() {
        
        // Persistent opening
        if ($this->persistent == true && array_key_exists(get_class($this).$this->binary.$this->movieFile, self::$persistentBuffer)) {
            return self::$persistentBuffer[get_class($this).$this->binary.$this->movieFile];
        } 

        return $this->_output;
    }

    /**
     * Setting parsable output
     * 
     * @param string $output
     */    
    public function setOutput($output) {
        
        $this->_output = $output;
        
        // Storing persistent opening
        if ($this->persistent == true) {
            self::$persistentBuffer[get_class($this).$this->binary.$this->movieFile] = $output;            
        }
    }
}