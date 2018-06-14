<?php

# ======================================================================================================= #

namespace App\I;

interface IDate
{
    public function isEqual(\App\M\Date $date);
    public function isAtLeastEqual(\App\M\Date $date);
    public function isAtLeastToday();
}
