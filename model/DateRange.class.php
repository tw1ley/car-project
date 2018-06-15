<?php

# ======================================================================================================= #

namespace App\M;

class DateRange implements \App\I\IDateRange
{
    private $dateFrom = null;
    private $dateTo = null;

    /**
     *
     *
     */
    public function __construct(\App\M\Date $dateFrom, \App\M\Date $dateTo) {
        if (!$dateFrom->isNull() && !$dateTo->isNull() && $dateFrom->isAtLeastEqual($dateTo)) {
            $this->dateFrom = $dateFrom;
            $this->dateTo = $dateTo;
        }
    }

    /**
     *
     *
     */
    public function compareRange(\App\M\DateRange $range) {
        return (($range->dateFrom->time < $this->dateFrom->time && $range->dateTo->time < $this->dateFrom->time) ||
                ($range->dateFrom->time > $this->dateTo->time && $range->dateTo->time > $this->dateTo->time));
    }
}
