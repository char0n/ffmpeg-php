.. image:: https://circleci.com/gh/char0n/ffmpeg-php.svg?style=svg
    :target: https://circleci.com/gh/char0n/ffmpeg-php

FFmpegPHP
=========

FFmpegPHP is a pure OO PHP port of ffmpeg-php (written in C). It adds an easy to use,
object-oriented API for accessing and retrieving information from video and audio files.
It has methods for returning frames from movie files as images that can be manipulated
using PHP image functions. This works well for automatically creating thumbnail images from movies.
FFmpegPHP is also useful for reporting the duration and bitrate of audio files (mp3, wma...).
FFmpegPHP can access many of the video formats supported by ffmpeg (mov, avi, mpg, wmv...) 


Requirements
------------

- PHP 5.3 and higher
- ffmpeg or ffprobe


Tests
-----

**Tested environment**

- Xubuntu Linux 12.04.2 LTS precise 64-bit
- ffmpeg version N-37798-gcd1c12b
- PHPUnit 3.7.18
- PHP 5.3.10


**Running tests**

To run the test install phpunit (http://www.phpunit.de/) and run: ::

 $ phpunit --configuration="test/phpunit.xml"


Installation
------------

or download package from github.com: ::

 $ wget http://github.com/char0n/ffmpeg-php/tarball/master

or to install via composer (http://getcomposer.org/) place the following in your composer.json file: ::

 {
    "require": {
        "char0n/ffmpeg-php": "dev-master"
    }
 }


Using FFmpegPHP
---------------

Package downloaded from github.com and unpacked into certain directory: ::

 require_once 'PATH_TO_YOUR_DIRECTORY/FFmpegAutoloader.php';
 

Author
------

| char0n (Vladimír Gorej.)
| email: vladimir.gorej@gmail.com
| web: https://www.linkedin.com/in/vladimirgorej/

Documentation
-------------

FFmpegPHP API documentation: http://char0n.github.io/ffmpeg-php/

FFmpegPHP documentation can be build from source code 
using PhpDocumentor with following commnad: ::

 $ phpdoc -d . -t docs --ignore="test/*"



References
----------

- http://github.com/char0n/ffmpeg-php
- http://www.phpclasses.org/package/5977-PHP-Manipulate-video-files-using-the-ffmpeg-program.html
- http://freshmeat.net/projects/ffmpegphp
- http://www.phpdoc.org/
- http://www.phpunit.de/
- http://pear.php.net/
