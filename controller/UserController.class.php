<?php

# ======================================================================================================= #

namespace App\Controller;

class UserController extends \App\A\Controller
{
    /**
     * Method to proces Login View
     *
     */
    private function processLogin() {
        $user = $this->isLogged();

        if ($user) {
            $this->redirect('user/profile');
        }
        # === #
        $this->data['login'] = '';
        if (!empty($_POST['login-form']) && !empty($_POST['login']['login']) && !empty($_POST['login']['password'])) {
            $user = new \App\Model\UserManager();
            if ($user->login($_POST['login']['login'], $_POST['login']['password'])) {
                $this->redirect('user/profile');
            } else {
                $this->data['login'] = $_POST['login']['login'];
            }
        }
        $this->head = array(
            'title' => 'Logowanie',
            'description' => 'Logowanie do panelu pracownika',
        );
        $this->view = 'login';
    }

    /**
     * Method to proces logout View
     *
     */
    private function processLogout() {
        $user = $this->isLogged();

        if ($user) {
            $user->logout();
        }
        $this->redirect('user');
    }

    /**
     * Method to proces Profile View
     *
     */
    private function processProfile() {
        $user = $this->isLogged();

        if (!$user) {
            $this->redirect('user');
        }

        $this->head = array(
            'title' => 'Profil',
            'description' => 'Profil pracownika',
        );
        $this->view = 'profile';
    }

    /**
     *
     */
    public function process($parms) {
        if (empty($parms)) {
            $this->redirect('user/login');
        } elseif (count($parms) > 1) {
            $this->redirect('user/'.$parms[0]);
        }
        # === #

        // Get all avaiable views for User
        $views = getConfig('view', 'user');

        // Call speciefied function to process selected view
        if (in_array($parms[0], $views)) {
            $this->{'process'.$parms[0]}();
        } else {
            $this->redirect('user');
        }
    }
}
