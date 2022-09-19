<?php

namespace app\controllers;

use app\models\RegistryForm;
use app\models\User;

class UserController extends AppController
{
    public function actionRegister(): string
    {
        $registryForm = new RegistryForm();
        if ($registryForm->load(\Yii::$app->request->post()) && $registryForm->validate()) {
            $model = new User();
            //Здесь стоит шифровать пароль
            $model->registerUser($registryForm->getRegisterData());
            $session = \Yii::$app->session;
            $session->open();
            $session['login'] = $registryForm->login;
            \Yii::$app->response->redirect(['/profile']);
        }
        return $this->render('register', ['model' => $registryForm]);
    }

    public function actionProfile()
    {
        $this->view->params = ['menubar' => 1];
        return $this->render('profile');
    }

    public function actionLogin()
    {
        //Ajax для входа в профиль
    }

    public function actionLogout()
    {
        \Yii::$app->session->remove('login');
        \Yii::$app->session->destroy();
        \Yii::$app->response->redirect(['/']);
    }
}