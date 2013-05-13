<?php
/**
 * This is bootsrap file required for running all the tests
 * for FFmpegPHP package. This file manages all necessary
 * settings and file imports.
 * 
 * Testing framework: PHPUnit (http://www.phpunit.de)
 * 
 * @category tests
 * @package FFmpegPHPTest
 */

date_default_timezone_set('Europe/Prague');

spl_autoload_register(function($className) {
    $className = ltrim($className, '\\');
    $fileName  = '';
    $namespace = '';
    if ($lastNsPos = strrpos($className, '\\')) {
        $namespace = substr($className, 0, $lastNsPos);
        $className = substr($className, $lastNsPos + 1);
        $fileName  = str_replace('\\', DIRECTORY_SEPARATOR, $namespace) . DIRECTORY_SEPARATOR;
    }
    $fileName .= str_replace('_', DIRECTORY_SEPARATOR, $className) . '.php';

    $filePath = __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR .
               'main' . DIRECTORY_SEPARATOR . $fileName;
    if (file_exists($filePath)) {
        require_once $filePath;
    }
});