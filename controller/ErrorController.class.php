<?php

# ======================================================================================================= #

namespace App\C;

class ErrorController extends \App\A\Controller
{
    /**
     *
     *
     */
    public function process($params)
    {
        header("HTTP/1.0 404 Not Found");
        $this->head['title'] = 'Error 404';
        $this->head['description'] = 'Error 404';
        $this->view = 'error';
    }
}
