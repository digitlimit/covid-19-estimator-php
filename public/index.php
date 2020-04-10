<?php
declare(strict_types=1);
define('BASE_PATH', dirname(__DIR__));

require_once BASE_PATH . '/vendor/autoload.php';

use App\Lib\App;
use App\Lib\Router;
use App\Lib\Request;
use App\Lib\Response;
use App\Controller\EstimatorController;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;

function dd($data){
    var_dump($data);
    die();
}

function tell(){
    $log = new Logger('name');
    $log->pushHandler(new StreamHandler(BASE_PATH . "/logs.log", Logger::WARNING));
    return $log;
}

Router::post('/api/v1/on-covid-19', function (Request $request, Response $response)
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

Router::get('/api/v1/on-covid-19', function (Request $request, Response $response)
{

    $estimate = (new EstimatorController())->estimate($request->getRawJSON());
    $response->status(200)->toJSON($estimate);
});

Router::get('/api/v1/on-covid-19/(json|xml)', function (Request $request, Response $response)
{
    $estimate = (new EstimatorController())->estimate($request->getRawJSON());

    if($request->params[0] == 'json'){
        $response->status(200)->toJSON($estimate);
    }else{
        $response->status(200)->toXML($estimate);
    }
});


//$response = new Response();
//$response->status(404)->toJSON(['error' => "Not Found"]);

App::run();
