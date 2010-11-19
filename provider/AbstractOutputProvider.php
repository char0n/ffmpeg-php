/**
* AbstractOutputProvider parent of all output providers
* 
* @author char0n (Vladimir Gorej)
* @package FFmpegPHP
* @subpackage provider
* @license New BSD
* @version 2.5b1
*/
<?php
abstract class AbstractOutputProvider implements OutputProvider, Serializable {

    protected static $EX_CODE_FILE_NOT_FOUND = 334561;
    protected static $persistentBuffer       = array();
	
    protected $binary;
    protected $movieFile;
    protected $persistent;
	
    public function __construct($binary, $persistent) {
        $this->binary     = $binary;
        $this->persistent = $persistent;    
    }
    
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
?>
