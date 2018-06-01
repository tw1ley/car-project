<?php

# ======================================================================================================= #

namespace App\Model;

// Manages articles in the system
class ArticleManager
{
    // Returns an article from the database based on a URL
    public function getArticle($url) {
        return dbRow('SELECT `id`, `title`, `content`, `url`, `description` FROM `article` WHERE `url` = ?', array($url));
    }

    // Returns a list of all of the articles in the database
    public function getArticles() {
        return dbArray('SELECT `id`, `title`, `url`, `description` FROM `article` ORDER BY `id` DESC');
    }
}
