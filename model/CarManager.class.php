<?php

# ======================================================================================================= #

namespace App\M;

class CarManager implements \App\I\IGet
{
    public function getOne($url) {
        return dbRow('SELECT `id`, `url`, `title`, `description`, `name`, `content`, `foto` FROM `cars` WHERE `url` = ?', array($url));
    }

    public function getAll() {
        return dbArray('SELECT `id`, `url`, `title`, `description`, `name`, `content`, `foto` FROM `cars` ORDER BY `id` DESC');
    }

    public function reserve($userID, $carID, $dateFrom, $dateTo) {
        $dateFrom = new \App\M\Date(date(\App\M\Date::DATE_FORMAT, strtotime($dateFrom)));
        $dateTo = new \App\M\Date(date(\App\M\Date::DATE_FORMAT, strtotime($dateTo)));

        //debug($userID);
        //debug($carID);
        //debug($dateFrom);
        //debug($dateTo);

        return false;
    }
}
