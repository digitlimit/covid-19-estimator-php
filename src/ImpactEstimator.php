<?php
namespace Covid19Estimator;

class ImpactEstimator{

    protected $input;

    protected $output = [
        'data'          => [],  // the input data you got
        'impact'        => [], // your best case estimation
        'severeImpact'  => [] // your severe c
    ];

    public function __construct(array $input)
    {
        $this->input = $input;
        $this->output['data'] = $input;
    }

    protected function currentlyInfected($reported_cases, $estimated_cases){
        return $reported_cases * $estimated_cases;
    }

    protected function infectionsByRequestedTime($reported_cases, $estimated_cases){
        $currently_infected = $this->currentlyInfected($reported_cases, $estimated_cases);
        //there are 10 repeats of 3 days in 30days

        //calculate period
        $period = $this->calculatePeriods($this->input['periodType'], $this->input['timeToElapse']);

        return pow(2, $period) * $currently_infected;
    }

    protected function calculatePeriods($period_type, $time_to_elapse, $doubles=3)
    {
        $period = 1;

        //number of days hasn't doubled
        if($time_to_elapse <= $doubles) return $period;

        switch($period_type)
        {
            case 'days':
                $period = intdiv($time_to_elapse, $doubles);
                break;

            case 'weeks':
                //we have 7 days in a week
                $period = intdiv($time_to_elapse * 7, $doubles);
                break;

            case 'months':
                //TODO consider leap year etc
                //we have 30 days in a month
                $period = intdiv($time_to_elapse * 30, $doubles);
                break;
        }

        return $period;
    }

    protected function calculate(){

        //calculate impact
        $this->output['impact'] = [
            'currentlyInfected' => $this->currentlyInfected($this->input['reportedCases'], 10),
            'infectionsByRequestedTime' => $this->infectionsByRequestedTime($this->input['reportedCases'], 10),
        ];

        $this->output['severeImpact'] = [
            'currentlyInfected' => $this->currentlyInfected($this->input['reportedCases'], 50),
            'infectionsByRequestedTime' => $this->infectionsByRequestedTime($this->input['reportedCases'], 50),
        ];

        return $this;
    }

    public function toArray(){
        return $this->calculate()->output;
    }

    public function toJson(){
        return json_encode($this->calculate()->output, JSON_FORCE_OBJECT);
    }
}