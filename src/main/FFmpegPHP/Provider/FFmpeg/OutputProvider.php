<?php
/**
 * \FFmpegPHP\Provider\FFmpeg\OutputProvider ffmpeg Provider implementation.
 * 
 * @author char0n (Vladimír Gorej, gorej@codescale.net)
 * @package FFmpegPHP
 * @subpackage Provider
 * @license New BSD
 */
namespace FFmpegPHP\Provider\FFmpeg {

    use \FFmpegPHP\Provider\AbstractOutputProvider;
    use \Exception;

    class OutputProvider extends AbstractOutputProvider {

        protected static $EX_CODE_NO_FFMPEG = 334560;

        /**
         * Constructor
         *
         * @param string $ffmpegBinary path to ffmpeg executable
         * @param boolean $persistent persistent functionality on/off
         */
        public function __construct($ffmpegBinary = 'ffmpeg', $persistent = false) {
            parent::__construct($ffmpegBinary, $persistent);
        }

        /**
         * Getting parsable output from ffmpeg binary.
         *
         * @return string
         * @throws \Exception
         */
        public function getOutput() {

            // Persistent opening.
            if ($this->persistent == true && array_key_exists(get_class($this).$this->binary.$this->movieFile, self::$persistentBuffer)) {
                return self::$persistentBuffer[get_class($this).$this->binary.$this->movieFile];
            }

            // File doesn't exist.
            if (!file_exists($this->movieFile)) {
                throw new \Exception('Movie file not found', self::$EX_CODE_FILE_NOT_FOUND);
            }

            // Get information about file from ffmpeg.
            $output = array();

            exec($this->binary.' -i '.escapeshellarg($this->movieFile).' 2>&1', $output, $retVar);
            $output = join(PHP_EOL, $output);

            // ffmpeg installed.
            if (!preg_match('/FFmpeg version/i', $output)) {
                throw new Exception('FFmpeg is not installed on host server', self::$EX_CODE_NO_FFMPEG);
            }

            // Storing persistent opening.
            if ($this->persistent == true) {
                self::$persistentBuffer[get_class($this).$this->binary.$this->movieFile] = $output;
            }

            return $output;
        }
    }
}