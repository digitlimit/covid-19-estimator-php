<?php
require_once dirname(__DIR__) . '/vendor/autoload.php';

use Covid19Estimator\ImpactEstimator;
use Covid19Estimator\Impact;

function covid19ImpactEstimator($data)
{
    $impact = new Impact($data, 10);

    $severe_impact = new Impact($data, 50);

    $output = [
        'data' => $data,
        'impact' => [
            'currentlyInfected' => $impact->getCurrentlyInfected(),
            'infectionsByRequestedTime' => $impact->getInfectionsByRequestedTime(),
            'severeCasesByRequestedTime' => $impact->getSevereCasesByRequestedTime(15),
            'hospitalBedsByRequestedTime' => $impact->getHospitalBedsByRequestedTime(15, 35),
            'casesForICUByRequestedTime' => $impact->getCasesForICUByRequestedTime(5),
            'casesForVentilatorsByRequestedTime' => $impact->getCasesForVentilatorsByRequestedTime(2),
            'dollarsInFlight' => $impact->getDollarsInFlight()
        ],
        'severeImpact' => [
            'currentlyInfected' => $severe_impact->getCurrentlyInfected(),
            'infectionsByRequestedTime' => $severe_impact->getInfectionsByRequestedTime(),
            'severeCasesByRequestedTime' => $severe_impact->getSevereCasesByRequestedTime(15),
            'hospitalBedsByRequestedTime' => $severe_impact->getHospitalBedsByRequestedTime(15, 35),
            'casesForICUByRequestedTime' => $severe_impact->getCasesForICUByRequestedTime(5),
            'casesForVentilatorsByRequestedTime' => $severe_impact->getCasesForVentilatorsByRequestedTime(2),
            'dollarsInFlight' => $severe_impact->getDollarsInFlight()
        ]
    ];

    return $output;
}


//$c = covid19ImpactEstimator([
//    'region' => [
//        'name'                       => 'Africa',
//        'avgAge'                     => 19.7,
//        'avgDailyIncomeInUSD'        => 4,
//        'avgDailyIncomePopulation'   => 0.73
//    ],
//    'periodType'                    => "days",
//    'timeToElapse'                  => 38,
//    'reportedCases'                 => 2747,
//    'population'                    => 92931687,
//    'totalHospitalBeds'             => 678874
//]);
//
//print("<pre>".print_r($c,true)."</pre>");
//
//function dd($args){
//    $args = func_get_args();
//    call_user_func_array('dump', $args);
//    die();
//}