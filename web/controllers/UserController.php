<?php

declare(strict_types=1);

namespace app\controllers;

use app\models\LoginForm;
use app\models\Statistic;
use app\models\User;
use app\models\RegisterForm;
use yii\base\Exception;
use yii\web\Response;
use Yii;

class UserController extends AppController
{
    /**
     * Страница регистрации пользователя,
     * если пользователь залогинен, то переадресует на домашнюю страницу.
     * @throws Exception
     */
    public function actionRegister(): Response|string
    {
        if (!Yii::$app->user->isGuest) {
            $this->goHome();
        }

        $registerForm = new RegisterForm();

        if ($registerForm->load(Yii::$app->request->post()) && $registerForm->validate()) {
            $user = new User();
            $user
                ->setUsername($registerForm->username)
                ->setEmail($registerForm->email)
                ->setPassword($registerForm->password)
                ->save();
            $statistics = new Statistic();
            $statistics
                ->setOwnerId($user->getId())
                ->setOwner($registerForm->username)
                ->save();

            Yii::$app
                ->user
                ->login($user, true ? 3600 * 24 * 30 : 0);

            return $this->redirect('/profile');
        }

        return $this->render('register', ['registerForm' => $registerForm]);
    }

    /**
     * Разлогинивает пользователя и отправляет на главную страницу.
     */
    public function actionLogout(): Response
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    /**
     * Страница для входа пользователя, если пользователь уже залогинен - переправляет на главную страницу.
     */
    public function actionLogin(): Response|string
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $loginForm = new LoginForm();

        if ($loginForm->load(Yii::$app->request->post()) && $loginForm->validate()) {
            $user = User::find()
                ->byEmail($loginForm->email)
                ->one();
            Yii::$app
                ->user
                ->login($user, $loginForm->rememberMe ? 3600 * 24 * 30 : 0);

            return $this->redirect('/profile');
        }

        return $this->render('login', ['loginForm' => $loginForm]);
    }
}
