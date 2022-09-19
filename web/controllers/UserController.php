<?php

namespace app\controllers;

use app\models\RegistryForm;

class UserController extends AppController
{
    public function actionRegister(): string
    {
        $registryForm = new RegistryForm();
        if ($registryForm->load(\Yii::$app->request->post()) && $registryForm->validate()) {
            //Создаем запись в БД, логинимся, переводим на profile
        }
        return $this->render('register', ['model' => $registryForm]);
    }

    public function actionProfile()
    {
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