<?php
namespace Covid19Estimator;

use Covid19Estimator\Helper;

class Impact{

    protected $input;

    protected $estimated_cases;

    protected $currently_affected;

    protected $infections_by_requested_time;

    public function __construct(array $input, $estimated_cases)
    {
        $this->input = $input;
        $this->estimated_cases = $estimated_cases;
        $this->currently_affected = $this->currentlyInfected($estimated_cases);
        $this->infections_by_requested_time = $this->infectionsByRequestedTime($this->currently_affected);
    }

    /**
     * Calculate currently infected
     *
     * @param $estimated_cases
     * @return int
     */
    protected function currentlyInfected(int $estimated_cases) : int{
        return $this->input['reportedCases'] * $estimated_cases;
    }

    /**
     * Calculate infection by rested time
     *
     * @param $currently_infected
     * @return int
     */
    protected function infectionsByRequestedTime(int $currently_infected) : int{
        //e.g 2 ^ 12
        $period_time_estimate = 2 ** Helper::getPeriodTimeEstimatedNumberOfInfected($this->input);
        return $period_time_estimate * $currently_infected;
    }

    public function getInfectionsByRequestedTime() : int{
        return $this->infections_by_requested_time;
    }

    public function getCurrentlyInfected() : int{
        return $this->currently_affected;
    }

    public function getSevereCasesByRequestedTime(int $percentage) : int
    {
        return intval($this->getInfectionsByRequestedTime() * ($percentage / 100));
    }

    public function getHospitalBedsByRequestedTime(int $percentage, int $beds_percentage)
    {
        $available_beds = $this->input['totalHospitalBeds'] * ($beds_percentage/100);
        return intval($available_beds - $this->getSevereCasesByRequestedTime($percentage));
    }

    public function getCasesForICUByRequestedTime(int $percentage) : int
    {
        return intval($this->getInfectionsByRequestedTime() * ($percentage / 100));
    }

    public function getCasesForVentilatorsByRequestedTime(int $percentage) : int
    {
        return intval($this->getInfectionsByRequestedTime() * ($percentage / 100));
    }

    public function getDollarsInFlight(){

        $days = Helper::resolveTimeToElapseToDays($this->input);

        $cases = $this->getInfectionsByRequestedTime() *
            $this->input['region']['avgDailyIncomePopulation'] *
            $this->input['region']['avgDailyIncomeInUSD'] *
            intdiv($days, 3);

        return number_format($cases, 2, '.', '');
    }
}