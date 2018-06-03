<?php

# ======================================================================================================= #

namespace App\Model;

class CarManager implements \App\I\IGet
{
    public function getOne($url) {
        return dbRow('SELECT `id`, `url`, `title`, `description`, `name`, `content` FROM `cars` WHERE `url` = ?', array($url));
    }

    public function getAll() {
        return dbArray('SELECT `id`, `url`, `title`, `description`, `name`, `content` FROM `cars` ORDER BY `id` DESC');
    }
}
