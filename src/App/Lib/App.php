<?php namespace App\Lib;

class App
{
    public static function run()
    {
        Logger::enableSystemLogs();
    }

    public static function log()
    {
        $end_time = microtime(true);
        $method = $_SERVER['REQUEST_METHOD'];
        $path = $_SERVER['REQUEST_URI'];
        $time = floor(($end_time - START_TIME)  * 1000);

        $time = sprintf("%02d", $time); //prefix with leading zero if single

        $log = "{$method}\t\t{$path}\t\t200\t\t{$time} ms".PHP_EOL;
        file_put_contents(BASE_PATH . "/log.txt", $log, FILE_APPEND);
    }
}
