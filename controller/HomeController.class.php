<?php

# ======================================================================================================= #

namespace App\C;

class HomeController extends \App\A\Controller
{
    /**
     *
     *
     */
    public function process($parms) {
        $this->head['title'] = 'Home';
        $this->head['description'] = 'Home Page';

        $this->view = 'home';
    }
}
