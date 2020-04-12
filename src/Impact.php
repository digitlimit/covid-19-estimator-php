<?php
namespace Covid19Estimator;

use Covid19Estimator\Helper;

/**
 * Class Impact
 * @package Covid19Estimator
 */
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

    /**
     * Get currently infected
     *
     * @return int
     */
    public function getCurrentlyInfected() : int{
        return $this->currently_affected;
    }

    /**
     * Get severe cases by requested time
     *
     * @param int $percentage
     * @return int
     */
    public function getSevereCasesByRequestedTime(int $percentage) : int
    {
        return intval($this->getInfectionsByRequestedTime() * ($percentage / 100));
    }

    /**
     * Get Hospital beds by requested time
     *
     * @param int $percentage
     * @param int $beds_percentage
     * @return int
     */
    public function getHospitalBedsByRequestedTime(int $percentage, int $beds_percentage)
    {
        $available_beds = $this->input['totalHospitalBeds'] * ($beds_percentage/100);
        return intval($available_beds - $this->getSevereCasesByRequestedTime($percentage));
    }

    /**
     * Get cases for ICU by request time
     *
     * @param int $percentage
     * @return int
     */
    public function getCasesForICUByRequestedTime(int $percentage) : int
    {
        return intval($this->getInfectionsByRequestedTime() * ($percentage / 100));
    }

    /**
     * Get cases for ventilators by requested time
     *
     * @param int $percentage
     * @return int
     */
    public function getCasesForVentilatorsByRequestedTime(int $percentage) : int
    {
        return intval($this->getInfectionsByRequestedTime() * ($percentage / 100));
    }

    /**
     * Get dollars in flight
     *
     * @return false|float
     */
    public function getDollarsInFlight(){

        $days = Helper::resolveTimeToElapseToDays($this->input);

        $dollars = (
                $this->getInfectionsByRequestedTime() *
                $this->input['region']['avgDailyIncomePopulation'] *
                $this->input['region']['avgDailyIncomeInUSD']
            ) / $days;

        return floor($dollars);
    }
}