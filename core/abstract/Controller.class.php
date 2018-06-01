<?php

# ======================================================================================================= #

namespace App\A;

abstract class Controller
{

    protected $data = array();
    protected $view = '';
    protected $head = array(
        'title' => '',
        'description' => ''
    );

    abstract function process($parms);

    public function renderView() {
        if ($this->view) {
            extract($this->data);
            require VIEW_DIR.$this->view.VIEW_EXT;
        }
    }

    public function redirect($url) {
        header("Location: /".$url);
        header("Connection: close");
        exit;
    }

}
