<?php
declare(strict_types=1);
define('BASE_PATH', dirname(__DIR__));
define('START_TIME', microtime(true));

require_once BASE_PATH . '/vendor/autoload.php';

use App\Lib\App;
use App\Lib\Router;
use App\Lib\Request;
use App\Lib\Response;
use App\Controller\EstimatorController;

Router::post('/api/v1/on-covid-19', function (Request $request, Response $response)
{
    $estimate = (new EstimatorController())->estimate($request->getRawJSON());
    $response->status(200)->toJSON($estimate);
});
Router::get('/api/v1/on-covid-19', function (Request $request, Response $response)
{
    $estimate = (new EstimatorController())->estimate($request->getRawJSON());
    $response->status(200)->toJSON($estimate);
});

Router::post('/api/v1/on-covid-19/(json|xml)', function (Request $request, Response $response)
{
    $estimate = (new EstimatorController())->estimate($request->getRawJSON());

    if($request->params[0] == 'json'){
        $response->status(200)->toJSON($estimate);
    }else{
        $response->status(200)->toXML($estimate);
    }
});
Router::get('/api/v1/on-covid-19/(json|xml)', function (Request $request, Response $response)
{
    $estimate = (new EstimatorController())->estimate($request->getRawJSON());

    if($request->params[0] == 'json'){
        App::log();
        $response->status(200)->toJSON($estimate);
    }else{
        App::log();
        $response->status(200)->toXML($estimate);
    }
});

Router::get('/api/v1/on-covid-19/logs', function (Request $request, Response $response)
{
    $log = file_get_contents(BASE_PATH . "/log.txt");
    $response->status(200)->toPlainText($log);
});

Router::post('/api/v1/on-covid-19/logs', function (Request $request, Response $response)
{
    $log = file_get_contents(BASE_PATH . "/log.txt");
    $response->status(200)->toPlainText($log);
});

