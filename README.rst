FFmpegPHP 2.6
=============

FFmpegPHP is a pure OO PHP port of ffmpeg-php writter in C. It adds an easy to use,
object-oriented API for accessing and retrieving information from video and audio files.
It has methods for returning frames from movie files as images that can be manipulated
using PHP's image functions. This works well for automatically creating thumbnail images from movies.
FFmpegPHP is also useful for reporting the duration and bitrate of audio files (mp3, wma...).
FFmpegPHP can access many of the video formats supported by ffmpeg (mov, avi, mpg, wmv...) 


Requirements
------------

- PHP 5.3 and higher
- ffmpeg or ffprobe


Tests
-----

**Tested on evnironment**

- Xubuntu Linux 11.10 oneiric 64-bit
- ffmpeg version N-37798-gcd1c12b
- PHPUnit 3.5.15
- PHP 5.3.6


**Running tests**

To run the test install phpunit (http://www.phpunit.de/) and run: ::

 $ phpunit --bootstrap test/bootstrap.php test/


Installation
------------

You can easily install FFmpegPHP via PEAR framework: ::

 $ sudo pear channel-discover pear.codescale.net
 $ sudo pear install codescale/FFmpegPHP2

or download package from github.com: ::

 $ wget http://github.com/char0n/ffmpeg-php/tarball/master

or to install via composer (http://getcomposer.org/) place the following in your composer.json file: ::

 {
    "require": {
        "codescale/ffmpeg-php": "dev-master"
    }
 }


Using FFmpegPHP
---------------

Package installed via PEAR channel: ::

 require_once 'FFmpegPHP2/FFmpegAutoloader.php';

Package downloaded from github.com and unpacked into certain directory: ::

 require_once 'PATH_TO_YOUR_DIRECTORY/FFmpegAutoloader.php';
 

Author
------

| char0n (Vladimír Gorej, CodeScale s.r.o.)
| email: gorej@codescale.net
| web: http://www.codescale.net

Documentation
-------------

FFmpegPHP documentation can be build from source code 
using PhpDocumentor with following commnad: ::

 $ phpdoc -o HTML:Smarty:HandS -d . -t docs


References
----------

- http://github.com/CodeScaleInc/ffmpeg-php
- http://www.phpclasses.org/package/5977-PHP-Manipulate-video-files-using-the-ffmpeg-program.html
- http://freshmeat.net/projects/ffmpegphp
- http://www.codescale.net/en/community/#ffmpegphp
- http://pear.codescale.net/
- http://www.phpdoc.org/
- http://www.phpunit.de/
- http://pear.php.net/
