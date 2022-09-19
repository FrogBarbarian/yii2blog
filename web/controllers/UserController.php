<?php

namespace app\controllers;

use app\models\User;

class UserController extends AppController
{
    public function actionRegister(): string
    {
        if (isset($_POST['login'])) {
            $model = new User();
        }


        $this->view->title = 'Регистрация';
        return $this->render('register');
    }

    public function actionProfile()
    {
        $this->view->title = 'Профиль';
        return $this->render('profile');
    }

    public function actionLogin()
    {
        //Ajax для входа в профиль
    }

    public function actionLogout()
    {
        //Здесь только редирект и выход
    }
}