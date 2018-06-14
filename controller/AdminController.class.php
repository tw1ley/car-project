<?php

# ======================================================================================================= #

namespace App\C;

class AdminController extends \App\A\Controller
{
    /**
     *
     *
     */

    public function processHome() {
        $this->head['title'] = 'Panel administratora';
        $this->head['description'] = 'Panel administratora';
        $this->view = 'home';
    }

    /**
     *
     *
     */

    public function processUser() {
        if (!empty($_GET['edit'])) {
            $this->data['post']['user']['add'] = dbRow("SELECT * FROM `user` WHERE `id` = ".(int) $_GET['edit']);
        }

        if (!empty($_GET['delete'])) {
            dbQuery("DELETE FROM `user` WHERE `id` = ".(int) $_GET['delete']);
            $this->redirect('admin/user');
        }

        if (!empty($_POST['user']['add']) && !empty($_POST['user']['form'])) {
            if (!empty($_POST['user']['add']['id'])) {
                dbQuery("UPDATE `user` SET `name` = :name, `surname` = :surname, `email` = :email, `phone` = :phone, `city` = :city, `login` = :login, `password` = :password, `description` = :description WHERE `id` = :id", array(
                    'name' => $_POST['user']['add']['name'],
                    'surname' => $_POST['user']['add']['surname'],
                    'email' => $_POST['user']['add']['email'],
                    'phone' => $_POST['user']['add']['phone'],
                    'city' => $_POST['user']['add']['city'],
                    'login' => $_POST['user']['add']['login'],
                    'password' => $_POST['user']['add']['password'],
                    'description' => $_POST['user']['add']['description'],
                    'id' => $_POST['user']['add']['id']
                ));
            } else {
                dbQuery("INSERT INTO `user` (`name`, `surname`, `email`, `phone`, `city`, `login`, `password`, `description`) VALUES (:name, :surname, :email, :phone, :city, :login, :password, :description)", array(
                    'name' => $_POST['user']['add']['name'],
                    'surname' => $_POST['user']['add']['surname'],
                    'email' => $_POST['user']['add']['email'],
                    'phone' => $_POST['user']['add']['phone'],
                    'city' => $_POST['user']['add']['city'],
                    'login' => $_POST['user']['add']['login'],
                    'password' => !empty($_POST['user']['add']['id']) ? $_POST['user']['add']['password'] : \App\M\UserManager::passwordEncode($_POST['user']['add']['password']),
                    'description' => $_POST['user']['add']['description']
                ));
            }
            $this->redirect('admin/user');
        }

        $this->data['users'] = dbArray("SELECT * FROM `user`");

        $this->view = 'user';
    }

    /**
     *
     *
     */

    public function processArticle() {
        $this->view = 'article';
    }

    /**
     *
     *
     */

    public function processCar() {
        $this->view = 'car';
    }

    /**
     *
     *
     */

    public function process($parms) {
        if (!($user = $this->isAuth())) {
            $this->redirect('error');
        }

        # === #

        // Get all avaiable views for Admin
        $views = getConfig('view', 'admin');

        // Call speciefied function to process selected view
        if (in_array($parms[0], $views)) {
            $this->{'process'.$parms[0]}();
        } else {
            $this->redirect('admin/home');
        }

        $this->viewCatalog = 'admin';
    }
}
