<?php
require_once dirname(__DIR__) . '/vendor/autoload.php';

use Covid19Estimator\ImpactEstimator;

function covid19ImpactEstimator($data)
{
    $input = json_decode($data, true);

    $estimator = new ImpactEstimator($input);

//    print("<pre>".print_r($estimator->toArray(),true)."</pre>");

    return $estimator->toJson();
}

//$input = json_encode([
//    'region' => [
//        'name'                       => 'Africa',
//        'avgAge'                     => 19.7,
//        'avgDailyIncomeInUSD'        => 5,
//        'avgDailyIncomePopulation'   => 0.71
//    ],
//    'periodType'                    => "days",
//    'timeToElapse'                  => 58,
//    'reportedCases'                 => 674,
//    'population'                    => 66622705,
//    'totalHospitalBeds'             => 1380614
//], JSON_FORCE_OBJECT);
//
//$c = covid19ImpactEstimator($input);
//
//print($c);