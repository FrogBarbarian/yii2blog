<?php

namespace Web\Controllers;

class MainController extends AppController
{
    public function actionIndex()
    {
        $this->render('index');
    }

    public function actionAbout()
    {
        $this->render('about');
    }

}