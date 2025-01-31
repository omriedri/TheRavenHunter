<?php class TimeHelper {

    private const SECONDS = 0;
    private const MINUTES = 1;
    private const HOURS = 2;
    private const DAYS = 3;
    private const WEEKS = 4;
    private const MONTHS = 5;
    private const YEARS = 6;

    /**
     * Get now date
     * @return string
     */
    public static function getNow($format = 'Y-m-d H:i:s'): string {
        return date($format);
    }

    /**
     * Get today date
     * @return string
     */
    public static function getToday($format = 'Y-m-d'): string {
        return date($format);
    }

    /**
     * Get tomorrow date
     * @return string
     */
    public static function getTomorrow($format = 'Y-m-d'): string {
        return date($format, strtotime('+1 day'));
    }

    /**
     * Get yesterday date
     * @return string
     */
    public static function getYesterday($format = 'Y-m-d'): string {
        return date($format, strtotime('-1 day'));
    }

    /**
     * Validate given date
     * @param string $date
     * @param string $format
     * @return bool
     */
    public static function validateDate(string $date, $format = 'Y-m-d'): bool {
        $d = DateTime::createFromFormat($format, $date);
        return $d && $d->format($format) === $date;
    }

    /**
     * Switch date format
     * @param string $date
     * @param string $toFormat
     * @param string $fromFormat
     * @return string
     */
    public static function switchDateFormat($date, $toFormat = 'd/m/Y', $fromFormat = 'Y-m-d'): string {
        $d = DateTime::createFromFormat($fromFormat, $date);
        return $d->format($toFormat);
    }

    /**
     * Add time to a given date
     * @param string $time
     * @param int $value
     * @param int $type
     * @param string $format
     * @return string
     */
    public static function addTime($time, $value, $type = self::MINUTES, $format = 'Y-m-d H:i:s'): string {
        return self::modifyTimeByOperator($time, $value, $type, '+', $format)->format($format);
    }

    /**
     * Subtract time from a given date
     * @param string $time
     * @param int $value
     * @param int $type
     * @param string $format
     * @return string
     */
    public static function subTime($time, $value, $type = self::MINUTES, $format = 'Y-m-d H:i:s'): string {
        return self::modifyTimeByOperator($time, $value, $type, '-', $format)->format($format);
    }

    /**
     * Modify time by operator
     * @param string $time
     * @param int $value
     * @param int $type
     * @param string $operator
     * @return DateTime
     */
    private static function modifyTimeByOperator($time, $value, $type, $operator): DateTime {
        $date = new DateTime($time);
        switch ((int) $type) {
            case self::SECONDS:     $date->modify("$operator$value seconds");   break;
            case self::MINUTES:     $date->modify("$operator$value minutes");   break;
            case self::HOURS:       $date->modify("$operator$value hours");     break;
            case self::DAYS:        $date->modify("$operator$value days");      break;
            case self::WEEKS:       $date->modify("$operator$value weeks");     break;
            case self::MONTHS:      $date->modify("$operator$value months");    break;
            case self::YEARS:       $date->modify("$operator$value years");     break;
        }
        return $date;
    }

    /**
     * Get the days count between two dates
     * @param string $date1
     * @param string $date2
     * @return integer
     */
    public static function getDaysBetweenDates($dfrom, $dto) {
        $date1 = new DateTime($dfrom);
        $date2 = new DateTime($dto);
        $interval = $date1->diff($date2);
        return $interval->format('%a');
    }

    /**
     * Get the dates range from a given date and nights count
     * @param string $dfrom
     * @param int $nights
     * @return string[]
     */
    public static function getDatesRange($dfrom, $nights): array {
        $dates = [];
        $date = new DateTime($dfrom);
        $dates[] = $date->format('Y-m-d');
        for ($i = 1; $i < $nights; $i++) {
            $date->modify('+1 day');
            $dates[] = $date->format('Y-m-d');
        }
        return $dates;
    }

    /**
     * Get friendly time from seconds
     *
     * @param [type] $seconds
     * @return void
     */
    public static function secondsToTime($seconds) {
        $dtF = new DateTime("@0");
        $dtT = new DateTime("@$seconds");
        return $dtF->diff($dtT)->format('%H:%I:%S');
    }

    /**
     * Get friendly time from miliseconds
     *
     * @param int $miliseconds
     * @return string
     */
    public static function milisecondsToTime(int $miliseconds) {
        $seconds = $miliseconds / 1000;
        $dtF = new DateTime("@0");
        $dtT = new DateTime("@$seconds");
        return $dtF->diff($dtT)->format('%H:%I:%S');
    }
}