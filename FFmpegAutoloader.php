<?php
/**
 * FFmpegAutoloader manages lazy autoloading of all FFmpegPHP components
 * 
 * @author char0n (Vladimír Gorej, gorej@codescale.net)
 * @package FFmpegPHP
 * @license New BSD
 * @version 2.6
 */
class FFmpegAutoloader {
    /**
     * Map of all required FFmpegPHP package files
     *
     * @var array
     */
    protected static $classes;

    protected static function initClasses() {
        if (self::$classes === null) {
            self::$classes = array(
                'FFmpegAnimatedGif'      => 'AnimatedGif.php',
                'FFmpegFrame'            => 'Frame.php',
                'FFmpegMovie'            => 'Movie.php',
                'ffmpeg_animated_gif'    => 'Adapter'.DIRECTORY_SEPARATOR.'ffmpeg_animated_gif.php',
                'ffmpeg_frame'           => 'Adapter'.DIRECTORY_SEPARATOR.'ffmpeg_frame.php',
                'ffmpeg_movie'           => 'Adapter'.DIRECTORY_SEPARATOR.'ffmpeg_movie.php',
                'OutputProvider'         => 'Provider'.DIRECTORY_SEPARATOR.'OutputProvider.php',
                'AbstractOutputProvider' => 'Provider'.DIRECTORY_SEPARATOR.'AbstractOutputProvider.php',
                'FFmpegOutputProvider'   => 'Provider'.DIRECTORY_SEPARATOR.'OutputProvider.php',
                'FFprobeOutputProvider'  => 'Provider'.DIRECTORY_SEPARATOR.'OutputProvider.php',
                'StringOutputProvider'   => 'Provider'.DIRECTORY_SEPARATOR.'OutputProvider.php'
            );
        }
    }

    /**
     * Autoloading mechanizm
     * 
     * @param string $className name of the class to be loaded
     * @return boolean
     */
    public static function autoload($className) {
        if (array_key_exists($className, self::$classes)) {
            require_once dirname(__FILE__).DIRECTORY_SEPARATOR.self::$classes[$className];
            return true;
        }
        return false;
    }

    /**
     * Registering autoloading mechanizm
     */     
    public static function register() {
        if (function_exists('__autoload')) {        
            trigger_error('FFmpegPHP uses spl_autoload_register() which will bypass your __autoload() and may break your autoloading', E_USER_WARNING);    
        } else {        
            self::initClasses();
            spl_autoload_register(array('FFmpegAutoloader', 'autoload'));
        }
    }
}

FFmpegAutoloader::register();