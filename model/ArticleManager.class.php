<?php

# ======================================================================================================= #

namespace App\M;

class ArticleManager implements \App\I\IGet
{
    /**
     *
     *
     */
    public function getOne($url) {
        return dbRow('SELECT `id`, `url`, `alias`, `title`, `description`, `name`, `content` FROM `article` WHERE `url` = ?', array($url));
    }

    /**
     *
     *
     */
    public function getAll() {
        return dbArray('SELECT `id`, `url`, `alias`, `name` FROM `article` ORDER BY `id`');
    }

    /**
     *
     *
     */
    public function getMenu() {
        $sites = $this->getAll();
        if ($sites) {
            foreach ($sites as $key => $site) {
                $sites[$key]['url'] = 'article/'.$site['url'];
            }
        }
        return $sites;
    }
}
