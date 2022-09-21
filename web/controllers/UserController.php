<?php

namespace app\controllers;

use app\models\LoginForm;
use app\models\RegistryForm;
use app\models\User;

class UserController extends AppController
{
    public function actionRegister(): string
    {
        $registryForm = new RegistryForm();
        $loginForm = new LoginForm();
        if ($registryForm->load(\Yii::$app->request->post()) && $registryForm->validate()) {
            $model = new User();
            //TODO: реализовать шифрование пароля через модель
            //TODO: реализовать отправку email о регистрации через модель (подключить mailer)
            $model->registerUser($registryForm->getRegisterData());
            $session = \Yii::$app->session;
            $session->open();
            $session['login'] = $registryForm->login;
            $this->redirect('/profile');
        }
        return $this->render('register', ['model' => $registryForm, 'loginFormClass' => $loginForm]);
    }

    public function actionLogin()
    {
        $loginForm = new LoginForm();
        if (\Yii::$app->request->isAjax) {
            \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            echo 1;
//            $loginForm->validate();
//            var_dump($loginForm->rules());
//            return 1;
        }
    }

    public function actionLogout()
    {
        \Yii::$app->session->remove('login');
        \Yii::$app->session->destroy();
        $this->redirect('/');
    }
}