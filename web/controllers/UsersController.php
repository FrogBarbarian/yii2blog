<?php

namespace app\controllers;

use app\models\LoginForm;
use app\models\Post;
use app\models\PostTmp;
use app\models\Statistics;
use app\models\User;
use app\models\RegisterForm;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use Yii;

class UsersController extends AppController
{
    /**
     * Страница регистрации пользователя, если пользователь залогинен, то переадресует на домашнюю страницу.
     * Если данные из формы провалидированы, то создается запись в БД с пользователем.
     * @return Response|string Переадресация на домашнюю/страницу пользователя | Вид "регистрация пользователя".
     * @throws NotFoundHttpException
     */
    public function actionRegister(): Response|string
    {
        if (Yii::$app->session->has('login')) {
            throw new NotFoundHttpException();
        }
        $model = new RegisterForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $user = new User();
            $user
                ->setLogin($model->login)
                ->setEmail($model->email)
                ->setPassword($model->password)
                ->save();
            $statistics = new Statistics();
            $statistics
                ->setOwner($model->login)
                ->save();
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
     * @throws NotFoundHttpException
     */
    public function actionLogout(): Response
    {
        $session = Yii::$app->session;
        if ($session->has('login')) {
            $session->removeAll();
            $session->destroy();
            return $this->goHome();
        }
        throw new NotFoundHttpException();
    }

    /**
     * Страница для входа пользователя, если пользователь уже залогинен - переправляет на главную страницу.
     * Логинит пользователя, если данные для входа корректны.
     * @return Response|string Переадресация на страницу пользователя | Страница входа пользователя.
     */
    public function actionLogin(): Response|string
    {
        if (Yii::$app->session->has('login')) {
            return $this->goHome();
        }
        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $user = User::find()
                ->byEmail($model->email)
                ->one();
            $session = Yii::$app->session;
            $session->open();
            $session['login'] = $user->getLogin();
            if ($user->getIsAdmin()) {
                $session['admin'] = true;
            }

            return $this->redirect('/profile');
        }

        return $this->render('login', ['model' => $model]);
    }

    //TODO: Add the comment
    public function actionUser(string $id = null, string $tab = 'overview')
    {
        $path = Yii::$app->request->getPathInfo();
        $session = Yii::$app->session;
        if ($path === 'user') {
            if ($id === null || $id < 1) {
                throw new NotFoundHttpException();
            }

            $user = User::find()
                ->byId($id)
                ->one();
            if ($user === null) {
                throw new NotFoundHttpException();
            }

            if ($user->getLogin() === $session['login']) {
                return $this->redirect('/profile');
            }
            $isOwn = false;
            $tab = 'overview';
        } else {
            if (!$session->has('login')) {
                return $this->redirect('/login');
            }

            $user = User::find()
                ->byLogin($session['login'])
                ->one();
            $isOwn = true;
        }

        $posts = Post::find()
            ->byAuthor($user->getLogin())
            ->orderDescById()
            ->all();
        $statistics = Statistics::find()
            ->byLogin($user->getLogin())
            ->one();

        if ($session->has('admin')) {
            $postsTmp = PostTmp::find()
            ->all();

        } else {
            $postsTmp = PostTmp::find()
                ->byAuthor($user->getLogin())
                ->all();
        }

        return $this->render('profile', [
            'user' => $user,
            'posts' => $posts,
            'postsTmp' => $postsTmp,
            'statistics' => $statistics,
            'isOwn' => $isOwn,
            'tab' => $tab,
        ]);
    }

    //TODO: add the comment
    public function actionChangeVisibility()
    {
        //TODO: Переделать под аякс
        if (!isset($_POST['_csrf'])) {
            throw new NotFoundHttpException();
        }
        $user = User::find()
            ->byId($_POST['id'])
            ->one();
        if (isset($_POST['hide'])) {
            $user
                ->setIsHidden(true)
                ->save();
        }
        if (isset($_POST['show'])) {
            $user
                ->setIsHidden(false)
                ->save();
        }

        return $this->redirect('/profile');
    }

    public function actionChangeSettings()
    {
    }
}
