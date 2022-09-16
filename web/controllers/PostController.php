<?php

namespace Web\Controllers;

class PostController extends AppController
{
    public function actionIndex()
    {
        $this->render('post/view');
    }

    public function actionTest()
    {
        $this->render('post/test');
    }
}