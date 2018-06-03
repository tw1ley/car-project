<?php

# ======================================================================================================= #

namespace App\Controller;

class CarController extends \App\A\Controller
{
    public function process($parms) {
        if (!($user = $this->isLogged())) {
            $this->redirect('user/');
        }

        $carManager = new \App\Model\CarManager();
        if (!empty($parms[0])) {
            $car = $carManager->getOne($parms[0]);

            if (!$car) {
                $this->redirect('error');
            }

            $this->data['car'] = $car;
            $this->head = array(
                'title' => $car['title'],
                'description' => $car['description'],
            );
            $this->view = 'car';
        } else {
            $cars = $carManager->getAll();

            $this->data['cars'] = $cars;
            $this->head['title'] = 'Car';
            $this->head['content'] = 'Car';
            $this->view = 'cars';
        }
    }
}
