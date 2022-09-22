<?php

namespace app\controllers;

class MainController extends AppController
{
    public function actionIndex(): string
    {
        $this->view->params = ['menubar' => 1];
        return $this->render('index', ['loginFormClass' => $this->loginForm]);
    }
}