<?php
date_default_timezone_set('Europe/Bratislava');  

$basePath = dirname(__FILE__).DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR;
require_once $basePath.'FFmpegAnimatedGif.php';
require_once $basePath.'FFmpegFrame.php';
require_once $basePath.'FFmpegMovie.php';
require_once $basePath.'adapter'.DIRECTORY_SEPARATOR.'ffmpeg_animated_gif.php';
require_once $basePath.'adapter'.DIRECTORY_SEPARATOR.'ffmpeg_frame.php';
require_once $basePath.'adapter'.DIRECTORY_SEPARATOR.'ffmpeg_movie.php';
require_once $basePath.'phpunit'.DIRECTORY_SEPARATOR.'PHPUnit'.DIRECTORY_SEPARATOR.'Framework'.DIRECTORY_SEPARATOR.'TestCase.php';
require_once $basePath.'phpunit'.DIRECTORY_SEPARATOR.'PHPUnit'.DIRECTORY_SEPARATOR.'Framework'.DIRECTORY_SEPARATOR.'TestSuite.php';
?>