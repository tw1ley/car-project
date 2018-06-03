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
            // Escape variables
            extract($this->protect($this->data));
            // Create variables with prefix undescore which contain no escaped values
            extract($this->data, EXTR_PREFIX_ALL, "");
            // Include template
            require VIEW_DIR.$this->view.VIEW_EXT;
        }
    }

    public function redirect($url) {
        header("Location: /".$url);
        header("Connection: close");
        exit;
    }

    private function protect($v = null) {
        if (!isset($v)) {
            return null;
        } elseif (is_string($v)) {
            return htmlspecialchars($v, ENT_QUOTES);
        } elseif (is_array($v)) {
            foreach($v as $key => $val) {
                $v[$key] = $this->protect($val);
            }
            return $v;
        } else {
            return $v;
        }
    }

    public function isLogged() {
        $user = new \App\Model\UserManager();
        return $user->logged() ? $user : null;
    }
}