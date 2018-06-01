<?php

# ======================================================================================================= #

namespace App\Controller;

class ArticleController extends \App\A\Controller
{
    public function process($parms) {
        $articleManager = new \App\Model\ArticleManager();
        $article = $articleManager->getArticle($parms[0]);

        if (!$article) {
            $this->redirect('error');
        }

        $this->head = array(
            'title' => $article['title'],
            'description' => $article['description'],
        );

        $this->data['title'] = $article['title'];
        $this->data['content'] = $article['content'];

        $this->view = 'article';
    }
}
