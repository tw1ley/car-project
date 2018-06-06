<?php

# ======================================================================================================= #

namespace App\C;

class ArticleController extends \App\A\Controller
{
    public function process($parms) {
        $articleManager = new \App\M\ArticleManager();
        $article = $articleManager->getOne($parms[0]);

        if (!$article) {
            $this->redirect('error');
        }

        $this->head['title'] = $article['title'];
        $this->head['description'] = $article['description'];

        $this->data['title'] = $article['title'];
        $this->data['content'] = $article['content'];

        $this->view = 'article';
    }
}
