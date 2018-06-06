<?php

# ======================================================================================================= #

namespace App\M;

class ArticleManager implements \App\I\IGet
{
    public function getOne($url) {
        return dbRow('SELECT `id`, `title`, `content`, `url`, `description` FROM `article` WHERE `url` = ?', array($url));
    }

    public function getAll() {
        return dbArray('SELECT `id`, `title`, `url`, `description` FROM `article` ORDER BY `id` DESC');
    }
}
