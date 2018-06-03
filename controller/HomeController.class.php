<?php

# ======================================================================================================= #

namespace App\Controller;

class HomeController extends \App\A\Controller
{
    public function process($parms) {
        $this->head = array(
            'title' => 'Home',
            'description' => 'Home Page',
        );

        $this->view = 'home';
    }
}
