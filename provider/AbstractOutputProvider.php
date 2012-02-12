<?php
/**
 * AbstractOutputProvider parent of all output providers
 * 
 * @author char0n (Vladimír Gorej, gorej@codescale.net)
 * @package FFmpegPHP
 * @subpackage provider
 * @abstract
 * @license New BSD
 * @version 2.6
 */
abstract class AbstractOutputProvider implements OutputProvider, Serializable {

    protected static $EX_CODE_FILE_NOT_FOUND = 334561;
    protected static $persistentBuffer       = array();
	
    /**
     * Binary that returns info about movie file
     * 
     * @var string
     */    
    protected $binary;
    
    /**
     * Movie File path
     * 
     * @var string
     */
    protected $movieFile;
    
    /**
     * Persistent functionality on/off
     * 
     * @var boolean
     */
    protected $persistent;
	
    /**
     * Base constructor for every provider
     * 
     * @param string $binary binary that returns info about movie file
     * @param boolean $persistent persistent functionality on/off
     */
    public function __construct($binary, $persistent) {
        $this->binary     = $binary;
        $this->persistent = $persistent;    
    }
    
    /**
     * Setting movie file path
     * 
     * @param string $movieFile
     */    
    public function setMovieFile($movieFile) {
        $this->movieFile = $movieFile;
    }
    
    public function serialize() {
        return serialize(array(
            $this->binary,
            $this->movieFile,
            $this->persistent
        ));
    }
    
    public function unserialize($serialized) {
        list(
            $this->binary,
            $this->movieFile,
            $this->persistent
        ) = unserialize($serialized);
    }
}