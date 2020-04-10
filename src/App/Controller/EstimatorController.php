<?php namespace App\Controller;

use Covid19Estimator\Impact;

class EstimatorController
{
    public function estimate(array $data)
    {
        return [
            'impact' => file_get_contents('php://input'),
        ];

        if(!$data){
           return [
                'data' => '',
                'impact' => file_get_contents('php://input'),
                'severeImpact' => ''
           ];
        }

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
