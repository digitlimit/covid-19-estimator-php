<?php
namespace Covid19Estimator;

/**
 * Class Helper
 * @package Covid19Estimator
 */
class Helper{

    /*
     * Calculate and return time to elapse
     *
     * @param $input
     * @return int
     */
    public static function getTimeToElapse(array $input) : int {
        if ($input['periodType'] === 'weeks') {
            return $input['timeToElapse'] * 7;
        }elseif ($input['periodType'] === 'months') {
            return $input['timeToElapse'] * 30;
        }else{
            return $input['timeToElapse'];
        }
    }

    /**
     * Resolve time to elapse to days
     *
     * @param array $input
     * @return int
     */
    public static function resolveTimeToElapseToDays(array $input) : int {
        if ($input['periodType'] == 'weeks') {
            //e.g timeToElapse=38, periodType=weeks = 38 x 7
            return $input['timeToElapse'] * 7;
        }elseif ($input['periodType'] == 'months') {
            //e.g timeToElapse=2, periodType=months = 2 x 30
            return $input['timeToElapse'] * 30;
        }else{
            //e.g timeToElapse=38, periodType=days = 38
            return $input['timeToElapse'];
        }
    }

    /**
     * Get estimated number of infected based on period time
     *
     * @param array $input
     * @param int $doubles_in_days
     * @return int
     */
    public static function getPeriodTimeEstimatedNumberOfInfected(array $input, $doubles_in_days=3) : int {
        return intdiv( self::resolveTimeToElapseToDays($input), $doubles_in_days);
    }
}