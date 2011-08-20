FFmpegPHP 2.5
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

- Xubuntu Linux 11.04 natty 64-bit
- ffmpeg version N-31898-g95b5b52
- PHPUnit 3.5.15
- PHP 5.3.5


**Running tests**

To run the test install phpunit (http://www.phpunit.de/) and run: ::

 phpunit --bootstrap test/bootstrap.php test/


Author
------

| char0n (Vladimír Gorej, CodeScale s.r.o.)
| email: gorej@codescale.net
| web: http://www.codescale.net

Documentation
-------------

FFmpegPHP documentation can be build from source code 
using PhpDocumentor with following commnad: ::

 phpdoc -o HTML:Smarty:HandS -d . -t docs


References
----------

- http://github.com/char0n/ffmpeg-php
- http://www.phpclasses.org/package/5977-PHP-Manipulate-video-files-using-the-ffmpeg-program.html
- http://freshmeat.net/projects/ffmpegphp
- http://www.codescale.net/en/community/#ffmpegphp
- http://www.phpdoc.org/
- http://www.phpunit.de/
