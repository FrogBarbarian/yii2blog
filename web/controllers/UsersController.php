<?php

namespace app\controllers;

use app\models\Admin;
use app\models\LoginForm;
use app\models\Profile;
use app\models\RegisterForm;
use yii\db\Exception;
use yii\web\Response;
use Yii;

class UsersController extends AppController
{
    /**
     * Страница регистрации пользователя, если пользователь залогинен, то переадресует на домашнюю страницу.
     * Если данные из формы провалидированы, то создается запись в БД с пользователем.
     * @return Response|string Переадресация на домашнюю/страницу пользователя | Страница регистрации нового пользователя.
     * @throws Exception
     */
    public function actionRegister(): Response|string
    {
        if (Yii::$app->session->has('login')) {
            return $this->goHome();
        }
        $model = new RegisterForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $model->registerUser();
            $session = Yii::$app->session;
            $session->open();
            $session['login'] = $model->login;
            return $this->redirect('/profile');
        }
        return $this->render('register', ['model' => $model]);
    }

    /**
     * Разлогинивает пользователя и отправляет на главную страницу.
     * @return Response
     */
    public function actionLogout(): Response
    {
        $session = Yii::$app->session;
        if ($session->has('login')) {
            $session->removeAll();
            $session->destroy();
            return $this->goHome();
        }
        return $this->goHome();
    }

    /**
     * Страница для входа пользователя, если пользователь уже залогинен - переправляет на главную страницу.
     * Логинит пользователя, если данные для входа корректны.
     * @return Response|string Переадресация на страницу пользователя | Страница входа пользователя.
     * @throws Exception
     */
    public function actionLogin(): Response|string
    {
        if (Yii::$app->session->has('login')) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $user = $model->getUser();
            $session = Yii::$app->session;
            $session->open();
            $session['login'] = $user['login'];
            if ($user['isAdmin']) {
                $session['admin'] = true;
            }
            return $this->redirect('/profile');
        }
        return $this->render('login', ['model' => $model]);
    }


    /**
     * Отображает профиль пользователя и позволяет взаимодействовать со своими данными и постами.
     * Если пользователь не залогинен, то отправляет пользователя на страницу логина.
     * @return Response|string Страница пользователя.
     * @throws Exception
     */
    public function actionProfile(): Response|string
    {
        if (!Yii::$app->session->has('login')) {
            return $this->redirect('/login');
        }

        $model = new Profile();
        $user = $model->getUser();
        $posts = $model->getUserPosts($user['login']);
        return $this->render('profile', [
            'model' => $model,
            'user' => $user,
            'posts' => $posts,
        ]);
    }
}
