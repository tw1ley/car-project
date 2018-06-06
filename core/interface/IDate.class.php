<?php

# ======================================================================================================= #

namespace App\I;

interface IDate
{
    public function compareTo(\App\M\Date $date);
}
