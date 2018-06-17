<?php

# ======================================================================================================= #

namespace App\C;

class CarController extends \App\A\Controller
{
    /**
     *
     *
     */
    private function processCar(&$parms, $user) {
        $carManager = new \App\M\CarManager();
        $car = $carManager->getOne($parms[0]);

        if (!$car) {
            $this->redirect('error');
        }

        # === #

        if (!empty($_POST['car-form']) && !empty($_POST['date']['from']) && !empty($_POST['date']['to'])) {
            $result = $carManager->setReservation($car['id'], $user->userID, $_POST['date']['from'], $_POST['date']['to']);
            $this->redirect('car/'.$parms[0].($result ? '/reserved' : '/not-reserved'));
        }

        # === #

        if ($_GET['delete'] && ($_GET['delete'] = (int) $_GET['delete'])) {
            $carManager->removeReservation($_GET['delete'], $user->userID);
            $this->redirect('car/'.$parms[0]);
        }

        # === #

        $this->data['car'] = $car;
        $this->data['info'] = !empty($parms[1]) ? $parms[1] : '';
        $this->data['reservations'] = $carManager->getReservations($this->data['car']['id'], null, true);

        $this->head['tile'] = $this->data['car']['title'];
        $this->head['description'] = $this->data['car']['description'];

        $this->view = 'car';
    }

    /**
     *
     *
     */
    private function processCars() {
        $carManager = new \App\M\CarManager();
        $cars = $carManager->getAll();

        # === #

        $this->data['cars'] = $cars;

        $this->head['title'] = 'Lista aut';
        $this->head['description'] = 'Lista dostępnych aut do wypożyczenia przez pracowników';

        $this->view = 'cars';
    }

    /**
     *
     *
     */
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
