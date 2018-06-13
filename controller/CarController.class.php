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

        # === #

        if (!empty($_POST['car-form']) && !empty($_POST['date']['from']) && !empty($_POST['date']['to'])) {
            $result = $carManager->reserve($this->data['car']['id'], $user->userID, $_POST['date']['from'], $_POST['date']['to']);
            $this->redirect('car/'.$parms[0]);
        }

        # === #

        if ($_GET['delete'] && ($_GET['delete'] = (int) $_GET['delete'])) {
            $carManager->removeReservation($_GET['delete'], $user->userID);
            $this->redirect('car/'.$parms[0]);
        }

        # === #

        $this->data['reservations'] = $carManager->getReservations($this->data['car']['id']);

        # === #

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
            $this->redirect('user');
        }
        if (!empty($parms[0])) {
            $this->processCar($parms, $user);
        } else {
            $this->processCars();
        }
    }
}
