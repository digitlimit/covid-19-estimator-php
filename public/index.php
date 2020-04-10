<?php
declare(strict_types=1);

require_once dirname(__DIR__) . '/vendor/autoload.php';

use App\Lib\App;
use App\Lib\Router;
use App\Lib\Request;
use App\Lib\Response;
use App\Controller\EstimatorController;

Router::post('/api/v1/on-covid-19', function (Request $request, Response $response)
{
    $estimate = (new EstimatorController())->estimate($request->getJSON());
    $response->status(200)->toJSON($estimate);
});

Router::post('/api/v1/on-covid-19/(json|xml)', function (Request $request, Response $response)
{
    $estimate = (new EstimatorController())->estimate($request->getJSON());

    if($request->params[0] == 'json'){
        $response->status(200)->toJSON($estimate);
    }else{
        $response->status(200)->toXML($estimate);
    }
});

$response = new Response();
$response->status(404)->toJSON(['error' => "Not Found"]);

App::run();
