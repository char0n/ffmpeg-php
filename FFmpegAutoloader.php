<?php
/**
 * FFmpegAutoloader manages lazy autoloading of all FFmpegPHP components
 * 
 * @author char0n (Vladimír Gorej, gorej@codescale.net)
 * @package FFmpegPHP
 * @license New BSD
 * @version 2.5
 */
class FFmpegAutoloader {

    protected static $classes = array(
        'FFmpegAnimatedGif'      => '/FFmpegAnimatedGif.php',
        'FFmpegFrame'            => '/FFmpegFrame.php',
        'FFmpegMovie'            => '/FFmpegMovie.php',
        'ffmpeg_animated_gif'    => '/adapter/ffmpeg_animated_gif.php',
        'ffmpeg_frame'           => '/adapter/ffmpeg_frame.php',
        'ffmpeg_movie'           => '/adapter/ffmpeg_movie.php',        
        'OutputProvider'         => '/provider/OutputProvider.php',
        'AbstractOutputProvider' => '/provider/AbstractOutputProvider.php',
        'FFmpegOutputProvider'   => '/provider/FFmpegOutputProvider.php',
        'FFprobeOutputProvider'  => '/provider/FFprobeOutputProvider.php'      
    );

    public static function autoload($className) {
        if (array_key_exists($className, self::$classes)) {
            require_once dirname(__FILE__).self::$classes[$className];
            return true;
        }
        return false;
    }

    public static function register() {
        if (function_exists('__autoload')) {        
            trigger_error('FFmpegPHP uses spl_autoload_register() which will bypass your __autoload() and may break your autoloading', E_USER_WARNING);    
        } else {
           spl_autoload_register(array('FFmpegAutoloader', 'autoload'));
        }
    }
}

FFmpegAutoloader::register();
?>
