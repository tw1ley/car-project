<?php

# ======================================================================================================= #

namespace App\C;

class AdminController extends \App\A\Controller
{
    /**
     *
     *
     */
    private function processHome() {
        $this->head['title'] = 'Panel administratora';
        $this->head['description'] = 'Panel administratora';
        $this->view = 'home';
    }

    /**
     *
     *
     */
    private function processUser() {
        if (!empty($_GET['edit'])) {
            $this->data['post']['user']['add'] = dbRow("SELECT * FROM `user` WHERE `id` = ".(int) $_GET['edit']);
        }

        if (!empty($_GET['delete'])) {
            if (dbQuery("DELETE FROM `user` WHERE `type` = 0 AND `id` = ".(int) $_GET['delete']) > 0) {
                dbQuery("DELETE FROM `cars_reservation` WHERE `id_user` = ".(int) $_GET['delete']);
            }
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
                    'password' => !empty($_POST['user']['add']['new_password']) ? \App\M\UserManager::passwordEncode($_POST['user']['add']['new_password']) : $_POST['user']['add']['password'],
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
                    'password' => \App\M\UserManager::passwordEncode($_POST['user']['add']['password']),
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
    private function processArticle() {
        if (!empty($_GET['edit'])) {
            $this->data['post']['article']['add'] = dbRow("SELECT * FROM `article` WHERE `id` = ".(int) $_GET['edit']);
        }

        if (!empty($_GET['delete'])) {
            dbQuery("DELETE FROM `article` WHERE `id` = ".(int) $_GET['delete']);
            $this->redirect('admin/article');
        }

        if (!empty($_POST['article']['add']) && !empty($_POST['article']['form'])) {
            if (!empty($_POST['article']['add']['id'])) {
                dbQuery("UPDATE `article` SET `url` = :url, `alias` = :alias, `title` = :title, `description` = :description, `name` = :name, `content` = :content WHERE `id` = :id", array(
                    'url' => $_POST['article']['add']['url'],
                    'alias' => $_POST['article']['add']['alias'],
                    'title' => $_POST['article']['add']['title'],
                    'description' => $_POST['article']['add']['description'],
                    'name' => $_POST['article']['add']['name'],
                    'content' => $_POST['article']['add']['content'],
                    'id' => $_POST['article']['add']['id']
                ));
            } else {
                dbQuery("INSERT INTO `article` (`url`, `alias`, `title`, `description`, `name`, `content`) VALUES (:url, :alias, :title, :description, :name, :content)", array(
                    'url' => $_POST['article']['add']['url'],
                    'alias' => $_POST['article']['add']['alias'],
                    'title' => $_POST['article']['add']['title'],
                    'description' => $_POST['article']['add']['description'],
                    'name' => $_POST['article']['add']['name'],
                    'content' => $_POST['article']['add']['content']
                ));
            }
            $this->redirect('admin/article');
        }

        $this->data['articles'] = dbArray("SELECT * FROM `article`");

        $this->view = 'article';
    }

    /**
     *
     *
     */
    private function processCar() {
        if (!empty($_GET['edit'])) {
            $this->data['post']['car']['add'] = dbRow("SELECT * FROM `cars` WHERE `id` = ".(int) $_GET['edit']);
        }

        if (!empty($_GET['delete'])) {
            if (dbQuery("DELETE FROM `cars` WHERE `id` = ".(int) $_GET['delete']) > 0) {
                dbQuery("DELETE FROM `cars_reservation` WHERE `id_car` = ".(int) $_GET['delete']);
            }
            $this->redirect('admin/car');
        }

        if (!empty($_POST['car']['add']) && !empty($_POST['car']['form'])) {
            if (!empty($_POST['car']['add']['id'])) {
                dbQuery("UPDATE `cars` SET `url` = :url, `title` = :titile, `description` = :description, `name` = :name, `content` = :content WHERE `id` = :id", array(
                    'url' => $_POST['car']['add']['url'],
                    'title' => $_POST['car']['add']['title'],
                    'description' => $_POST['car']['add']['description'],
                    'name' => $_POST['car']['add']['name'],
                    'content' => $_POST['car']['add']['content'],
                    'id' => $_POST['car']['add']['id']
                ));
            } else {
                dbQuery("INSERT INTO `cars` (`url`, `title`, `description`, `name`, `content`) VALUES (:url, :title, :description, :name, :content)", array(
                    'url' => $_POST['car']['add']['url'],
                    'title' => $_POST['car']['add']['title'],
                    'description' => $_POST['car']['add']['description'],
                    'name' => $_POST['car']['add']['name'],
                    'content' => $_POST['car']['add']['content']
                ));
            }
            $this->redirect('admin/car');
        }

        $this->data['cars'] = dbArray("SELECT * FROM `cars`");

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
