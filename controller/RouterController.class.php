<?php

# ======================================================================================================= #

namespace App\Controller;

class RouterController extends \App\A\Controller
{
    protected $controller;

    public function process($parms) {
        $parsedUrl = $this->parseUrl($parms['uri']);

        // Redirect to home page when no adress is speciefied
        if (empty($parsedUrl[0])) {
            $this->redirect('home');
        }

        // Create name of controller
        $controllerClass = $this->dashesToCamel(array_shift($parsedUrl)).'Controller';

        // Check if controller exists, if not redirect to error page
        if (file_exists(CTRL_DIR.$controllerClass.CTRL_EXT)) {
            $controllerClass = "\App\Controller\\$controllerClass";
            $this->controller = new $controllerClass();
        } else {
            $this->redirect('error');
        }

        $this->controller->process($parsedUrl);

        $this->data['base'] = $this->controller->data['base'] = locationBase();
        $this->data['url']  = $this->controller->data['url']  = locationUrl();
        $this->data['href'] = $this->controller->data['href'] = locationHref();

        $this->data['title'] = $this->controller->head['title'];
        $this->data['description'] = $this->controller->head['description'];

        $this->view = 'index';
    }

    private function parseUrl($url) {
        $parsedUrl = parse_url($url);
        $parsedUrl['path'] = trim(ltrim($parsedUrl['path'], '/'));

        return explode('/', $parsedUrl['path']);
    }

    private function dashesToCamel($text) {
        $text = str_replace('-', ' ', $text);
        $text = ucwords($text);
        $text = str_replace(' ', '', $text);

        return $text;
    }
}
