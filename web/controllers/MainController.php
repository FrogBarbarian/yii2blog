<?php

namespace app\controllers;


class MainController extends AppController
{
    public function actionIndex(): string
    {
        $this->view->params = ['menubar' => 1];
        $this->view->title = 'Главная страница';
        return $this->render('index');
    }
}