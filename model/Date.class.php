<?php

# ======================================================================================================= #

namespace App\M;

use \DateTime;

class Date implements \App\I\IDate
{
    public const YEAR = 'Y';
    public const MONTH = 'm';
    public const DAY = 'd';
    public const DATE_FORMAT = self::YEAR.'-'.self::MONTH.'-'.self::DAY;

    private $date = null;
    private $time = null;

    private $year = null;
    private $month = null;
    private $day  = null;

    public function __construct($date) {
        if (!empty($date) && is_string($date) && $this->validate($date)) {
            $this->date  = $date;
            $this->time  = strtotime($date);

            $this->year  = (int) date(self::YEAR, $this->time);
            $this->month = (int) date(self::MONTH, $this->time);
            $this->day   = (int) date(self::DAY, $this->time);
        }
    }

    private function validate($date) {
        $t = strtotime($date);
        return ($t && date(self::DATE_FORMAT, $t) === $date);
    }

    public function isEqual(\App\M\Date $date) {
        return $this->time == $date->time;
    }

    public function isAtLeastEqual(\App\M\Date $date) {
        return $this->time <= $date->time;
    }

    public function isAtLeastToday() {
        return $this->time >=strtotime(date(self::DATE_FORMAT));
    }

    public function isNull() {
        return is_null($this->date);
    }

    /**
     * Magin method
     * Get selected private values
     *
     */
    public function __get($name) {
        switch ($name) {
            case 'date' : {
                return $this->date;
            } break;
            case 'time' : {
                return $this->time;
            }
        }
        return null;
    }
}
