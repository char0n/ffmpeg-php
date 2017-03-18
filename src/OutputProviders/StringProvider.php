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

namespace Char0n\FFMpegPHP\OutputProviders;

class StringProvider extends AbstractProvider
{
    
    protected $output;

    /**
     * Constructor
     *
     * @param string $ffmpegBinary path to ffmpeg executable
     * @param boolean $persistent persistent functionality on/off
     */
    public function __construct($ffmpegBinary = 'ffmpeg', $persistent = false)
    {
        $this->output = '';
        parent::__construct($ffmpegBinary, $persistent);
    }
    
    /**
     * Getting parsable output from ffmpeg binary
     *
     * @return string
     */
    public function getOutput()
    {
        // Persistent opening
        $bufferKey = get_class($this).$this->binary.$this->movieFile;

        if (true === $this->persistent
            && array_key_exists($bufferKey, self::$persistentBuffer)
        ) {
            return self::$persistentBuffer[$bufferKey];
        }

        return $this->output;
    }

    /**
     * Setting parsable output
     *
     * @param string $output
     */
    public function setOutput($output)
    {
        $this->output = $output;
        
        // Storing persistent opening
        if (true === $this->persistent) {
            self::$persistentBuffer[get_class($this).$this->binary.$this->movieFile] = $output;
        }
    }
}
