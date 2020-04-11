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

    public static function getAllHeaders()
    {
        $headers = [];
        foreach ($_SERVER as $name => $value)
        {
            if (substr($name, 0, 5) == 'HTTP_')
            {
                $headers[str_replace(' ', '-', ucwords(strtolower(str_replace('_', ' ', substr($name, 5)))))] = $value;
            }
        }

        return $headers;
    }

    public static function server()
    {
        $headers = implode(' | ', self::getAllHeaders());
        file_put_contents(BASE_PATH . "/server.txt", $headers, FILE_APPEND);
    }
}
