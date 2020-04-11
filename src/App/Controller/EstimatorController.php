<?php namespace App\Controller;

use Covid19Estimator\Impact;
use App\Lib\App;

class EstimatorController
{
    public function estimate(array $data)
    {
        $raw = file_get_contents('php://input');
        $json = json_decode($raw, true);

        $data = [
            'region' => $json['region'],
            'periodType' => $json['periodType'],
            'timeToElapse' => $json['timeToElapse'],
            'reportedCases' => $json['reportedCases'],
            'population' => $json['population'],
            'totalHospitalBeds' => $json['totalHospitalBeds']
        ];

        $impact = new Impact($data, 10);

        $severe_impact = new Impact($data, 50);

        return [
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
    }
}
