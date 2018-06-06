<?php

# ======================================================================================================= #

namespace App\C;

class CarController extends \App\A\Controller
{
    private function processCar(&$parms, &$user) {
        $carManager = new \App\M\CarManager();

        $this->data['car'] = $carManager->getOne($parms[0]);

        if (!$this->data['car']) {
            $this->redirect('error');
        }

        if (!empty($_POST['car-form']) && !empty($_POST['date']['from']) && !empty($_POST['date']['to'])) {
            $result = $carManager->reserve($user->userID, $this->data['car']['id'], $_POST['date']['from'], $_POST['date']['to']);
            if (!$result) {
                $this->redirect('car/'.$this->data['car']['url'].'/error-add');
            }
        }

        if (!empty($parms[1])) {
            $this->data['error'] = $parms[1];
        }

        $this->head['tile'] = $this->data['car']['title'];
        $this->head['description'] = $this->data['car']['description'];
        $this->view = 'car';
    }

    private function processCars() {
        $carManager = new \App\M\CarManager();

        $this->data['cars'] = $carManager->getAll();
        $this->head['title'] = 'Car';
        $this->head['description'] = 'Car';
        $this->view = 'cars';
    }

    public function process($parms) {
        if (!($user = $this->isLogged())) {
            $this->redirect('user/');
        }
        if (!empty($parms[0])) {
            $this->processCar($parms, $user);
        } else {
            $this->processCars();
        }
    }
}
