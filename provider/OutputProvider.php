<?php
/**
* OutputProvider interface of all output providers
* 
* @author char0n (Vladimir Gorej)
* @package FFmpegPHP
* @subpackage provider
* @license New BSD
* @version 2.5b1
*/
interface OutputProvider {

    public function setMovieFile($movieFile);
    public function getOutput();
}
?>