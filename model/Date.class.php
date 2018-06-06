<?php

# ======================================================================================================= #

namespace App\M;

use \DateTime;

class Date implements \App\I\IDate
{
    public const YEAR = 'Y';
    public const MONTH = 'm';
    public const DAY = 'd';
    public const DATE_FORMAT = self::DAY.'-'.self::MONTH.'-'.self::YEAR;
    public const DATE_FORMAT_JS = 'DD-MM-YYYY';

    private $date = null;
    private $time = null;

    private $year = null;
    private $month = null;
    private $day  = null;

    public function __construct($date) {
        if (!empty($date) && is_string($date) && $this->validate($date)) {
            $this->date  = $date;
            $this->time  = strtotime($date);

            $this->year  = date(self::YEAR, $this->time);
            $this->month = date(self::MONTH, $this->time);
            $this->day   = date(self::DAY, $this->time);
        }
    }

    private function validate($date) {
        $t = strtotime($date);
        return ($t && date(self::DATE_FORMAT, $t) === $date);
    }

    public function compareTo(\App\M\Date $date) {
        debug($date);
    }
}
