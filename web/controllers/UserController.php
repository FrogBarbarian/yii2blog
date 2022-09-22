<?php

namespace app\controllers;

use app\models\RegistryForm;
use app\models\User;
use yii\web\Request;
use yii\widgets\ActiveForm;

class UserController extends AppController
{
    public function actionRegister(): string
    {
        $registryForm = new RegistryForm();
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

        return $this->render('register', ['model' => $registryForm, 'loginFormClass' => $this->loginForm]);
    }

    public function actionLogin()
    {
        $request = \Yii::$app->getRequest();
        $loginForm = $this->loginForm;

        if ($request->getIsAjax() || $request->getIsPost()) {
            $loginForm->setAttributes($request->post());
        }

        if ($request->getIsAjax() && isset($_REQUEST['ajax'])) {
            return $this->asJson(ActiveForm::validate($loginForm));
        }

        if ($request->getIsPost() && $loginForm->validate()) {
            $this->redirect('/profile');
        }
    }

    public function actionLogout()
    {
        \Yii::$app->session->remove('login');
        \Yii::$app->session->destroy();
        $this->redirect('/');
    }
}