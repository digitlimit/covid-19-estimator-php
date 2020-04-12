<?php namespace App\Lib;

/**
 * Class Router
 * @package App\Lib
 */
class Router
{
    /**
     * Handle GET requests
     *
     * @param $route
     * @param $callback
     */
    public static function get($route, $callback)
    {
        if (strcasecmp($_SERVER['REQUEST_METHOD'], 'GET') !== 0) {
            return;
        }

        self::on($route, $callback);
    }

    /**
     * Handle post requests
     *
     * @param $route
     * @param $callback
     */
    public static function post($route, $callback)
    {
        if (strcasecmp($_SERVER['REQUEST_METHOD'], 'POST') !== 0) {
            return;
        }

        self::on($route, $callback);
    }

    /**
     * Request helper
     *
     * @param $regex
     * @param $cb
     */
    public static function on($regex, $cb)
    {
        $params = $_SERVER['REQUEST_URI'];

        $params = (stripos($params, "/") !== 0) ? "/" . $params : $params;
        $regex = str_replace('/', '\/', $regex);
        $is_match = preg_match('/^' . ($regex) . '$/', $params, $matches, PREG_OFFSET_CAPTURE);

        if ($is_match) {
            // first value is normally the route, lets remove it
            array_shift($matches);
            // Get the matches as parameters
            $params = array_map(function ($param) {
                return $param[0];
            }, $matches);
            $cb(new Request($params), new Response());
        }
    }
}
