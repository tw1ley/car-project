<?php

# ======================================================================================================= #

namespace App\I;

interface IDateRange
{
    public function __construct(\App\M\Date $date1, \App\M\Date $date2);
    public function compareRange(\App\M\DateRange $date);
}
