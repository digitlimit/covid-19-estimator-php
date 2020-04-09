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
        $period = $this->calculatePeriods($this->input);

        return pow(2, $period) * $currently_infected;
    }

    protected function severeCasesByRequestedTime($reported_cases, $estimated_cases, $percentage)
    {
        $infected = $this->infectionsByRequestedTime($reported_cases, $estimated_cases) *
            ($percentage / 100);
        return floor($infected);
    }

    protected function casesForICUByRequestedTime($reported_cases, $estimated_cases, $percentage){
        $cases = $this->infectionsByRequestedTime($reported_cases, $estimated_cases) *
            ($percentage / 100);
        return floor($cases);
    }

    protected function dollarsInFlight($reported_cases, $estimated_cases){

        $time_to_elapse = $this->calculateTimeToElapse($this->input);

        $cases = $this->infectionsByRequestedTime($reported_cases, $estimated_cases) *
            $this->input['region']['avgDailyIncomePopulation'] *
            $this->input['region']['avgDailyIncomeInUSD'] *
            $time_to_elapse;

        return number_format($cases, 1, '.', '');
    }

    protected function casesForVentilatorsByRequestedTime($reported_cases, $estimated_cases, $percentage){
        $cases = $this->infectionsByRequestedTime($reported_cases, $estimated_cases) *
            ($percentage / 100);
        return floor($cases);
    }

    protected function hospitalBedsByRequestedTime($reported_cases, $estimated_cases, $percentage, $beds_percentage)
    {
        $available_beds = $this->calculateAvailableTotalHospitalBeds(
            $this->input['totalHospitalBeds'],
            $beds_percentage
        );

        $severe_case_requested_time = $this->severeCasesByRequestedTime(
            $reported_cases,
            $estimated_cases,
            $percentage
        );

        return floor($available_beds - $severe_case_requested_time);
    }

    protected function calculateAvailableTotalHospitalBeds($totalHospitalBeds, $percentage_available_beds){
        return ($percentage_available_beds / 100) * $totalHospitalBeds;
    }

    protected function calculatePeriods($input, $doubles=3)
    {
        $period_type = $input['periodType'];
        $time_to_elapse = $input['timeToElapse'];
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

    protected function calculateTimeToElapse($input){

        $period_type = $input['periodType'];
        $time_to_elapse = $input['timeToElapse'];

        switch($period_type)
        {
            case 'days':
                return $time_to_elapse;
                break;

            case 'weeks':
                return $time_to_elapse * 7;
                break;

            case 'months':
                return $time_to_elapse * 30;
                break;
        }
    }

    protected function calculate()
    {
        //calculate impact
        $this->output['impact'] = [
            'currentlyInfected' => $this->currentlyInfected($this->input['reportedCases'], 10),
            'infectionsByRequestedTime' => $this->infectionsByRequestedTime($this->input['reportedCases'], 10),
            'severeCasesByRequestedTime' => $this->severeCasesByRequestedTime($this->input['reportedCases'], 10, 15),
            'hospitalBedsByRequestedTime' => $this->hospitalBedsByRequestedTime(
                $reported_cases = $this->input['reportedCases'],
                $estimated_cases = 10,
                $percentage = 15,
                $beds_percentage = 35
            ),
            'casesForICUByRequestedTime' => $this->casesForICUByRequestedTime(
                $this->input['reportedCases'],
                $estimated_cases = 10,
                $percentage = 5
            ),
            'casesForVentilatorsByRequestedTime' => $this->casesForVentilatorsByRequestedTime(
                $this->input['reportedCases'],
                $estimated_cases = 10,
                $percentage = 2
            ),
            'dollarsInFlight' => $this->dollarsInFlight(
                $this->input['reportedCases'],
                $estimated_cases = 10
            )
        ];

        $this->output['severeImpact'] = [
            'currentlyInfected' => $this->currentlyInfected($this->input['reportedCases'], 50),
            'infectionsByRequestedTime' => $this->infectionsByRequestedTime($this->input['reportedCases'], 50),
            'severeCasesByRequestedTime' => $this->severeCasesByRequestedTime(
                $this->input['reportedCases'],
                $estimated_cases = 50,
                15
            ),
            'hospitalBedsByRequestedTime' => $this->hospitalBedsByRequestedTime(
                $reported_cases = $this->input['reportedCases'],
                $estimated_cases = 50,
                $percentage = 15,
                $beds_percentage = 35
            ),
            'casesForICUByRequestedTime' => $this->casesForICUByRequestedTime(
                $this->input['reportedCases'],
                $estimated_cases = 50,
                $percentage = 5
            ),
            'casesForVentilatorsByRequestedTime' => $this->casesForVentilatorsByRequestedTime(
                $this->input['reportedCases'],
                $estimated_cases = 50,
                $percentage = 2
            ),
            'dollarsInFlight' => $this->dollarsInFlight(
                $this->input['reportedCases'],
                $estimated_cases = 50
            )
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