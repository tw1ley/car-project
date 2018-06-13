<?php

# ======================================================================================================= #

namespace App\M;

class UserManager
{
    public const USER_TABLE = 'user';

    private $userID = null;
    private $userType = null;

    # === #

    /**
     * Static method used to encrypt password
     *
     */
    private static function passwordEncode($password = '') {
        return password_hash($password, PASSWORD_BCRYPT);
    }

    /**
     * Static method used to verify password
     *
     */
    private static function passwordVerify($password = '', $hash = '') {
        return password_verify($password, $hash);
    }

    # === #

    /**
     * Construct method, set standard values for instance
     *
     */
    public function __construct() {
        if (empty($_SESSION['user'])) {
            $_SESSION['user']['id'] = null;
            $_SESSION['user']['type'] = null;
        }
        $this->userID = &$_SESSION['user']['id'];
        $this->userType = &$_SESSION['user']['type'];
    }

    /**
     * Method with login functionality
     *
     */
    public function login($login = '', $password = '') {
        if (!$this->logged()) {
            if (!empty($login) && is_string($login) && !empty($password) && is_string($password)) {
                $user = dbRow("SELECT `id`, `login`, `password`, `type` FROM `".self::USER_TABLE."` WHERE `login` = ? LIMIT 1", array($login));
                if ($user && self::passwordVerify($password, $user['password'])) {
                    $this->userID = $user['id'];
                    $this->userType = $user['type'];
                    return true;
                }
            }
        }
        return false;
    }

    /**
     * Method used to check whenever User is logged
     *
     */
    public function logged() {
        return (!is_null($this->userID) && !is_null($this->userType));
    }

    /**
     * Method to check if user is Admin
     *
     */
    public function admin($admin = false) {
        if ($this->logged()) {
            return ($this->userType > 0);
        }
        return false;
    }

    /**
     * Method with logout funcionality
     *
     */
    public function logout() {
        if ($this->logged()){
            $this->userID = null;
            $this->userType = null;
            session_unset();
            session_destroy();
            setcookie(session_name(), '', time() - 42000, '/');
        }
    }

    /**
     * Get all information from database
     *
     */

     public function information() {
         if ($this->logged()) {
             return dbRow("SELECT `name`, `surname`, `email`, `phone`, `city`, `description` FROM `".self::USER_TABLE."` WHERE `id` = ".$this->userID);
         }
     }

    /**
     * Magin method
     * Get selected private values
     *
     */
    public function __get($name) {
        switch ($name) {
            case 'userID' : {
                return $this->userID;
            } break;
            case 'userType' : {
                return $this->userType;
            } break;
        }
        return null;
    }
}
