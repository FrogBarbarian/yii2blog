<?php

namespace app\controllers;


use app\models\LoginForm;

class MainController extends AppController
{
    public function actionIndex(): string
    {
        $loginForm = new LoginForm();
        $this->view->params = ['menubar' => 1];
        return $this->render('index', ['loginFormClass' => $loginForm]);
    }
}