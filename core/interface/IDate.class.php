<?php

# ======================================================================================================= #

namespace App\I;

interface IDate
{
    public function compareTo(\App\Model\Date $date);
}
