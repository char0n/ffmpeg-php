<?php
/**
 * This is bootsrap file required for running all the tests
 * for FFmpegPHP package. This file manages all necessary
 * settings and file imports.
 * 
 * Testing framework: PHPUnit (http://www.phpunit.de)
 * 
 * @category tests
 * @package FFmpegPHP  
 */

date_default_timezone_set('Europe/Bratislava');  

$basePath = dirname(__FILE__).DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR;
require_once $basePath.'FFmpegAutoloader.php';
require_once $basePath.'provider'.DIRECTORY_SEPARATOR.'OutputProvider.php';
require_once $basePath.'provider'.DIRECTORY_SEPARATOR.'AbstractOutputProvider.php';
require_once $basePath.'provider'.DIRECTORY_SEPARATOR.'FFmpegOutputProvider.php';
require_once $basePath.'provider'.DIRECTORY_SEPARATOR.'FFprobeOutputProvider.php';
require_once $basePath.'FFmpegAnimatedGif.php';
require_once $basePath.'FFmpegFrame.php';
require_once $basePath.'FFmpegMovie.php';
require_once $basePath.'adapter'.DIRECTORY_SEPARATOR.'ffmpeg_animated_gif.php';
require_once $basePath.'adapter'.DIRECTORY_SEPARATOR.'ffmpeg_frame.php';
require_once $basePath.'adapter'.DIRECTORY_SEPARATOR.'ffmpeg_movie.php';