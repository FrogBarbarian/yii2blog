<?php

declare(strict_types=1);

namespace app\controllers;

use app\models\Complaint;
use app\models\LoginForm;
use app\models\Post;
use app\models\TmpPost;
use app\models\Statistic;
use app\models\User;
use app\models\RegisterForm;
use yii\base\Exception;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use Yii;

class UsersController extends AppController
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

    /**
     * Показывает профиль пользователя.
     * @throws \Throwable
     */
    public function actionUser(string $id = null, string $tab = 'overview'): Response|string
    {
        $path = Yii::$app->request->getPathInfo();
        $user = Yii::$app->user->getIdentity();
        $id = (int)$id;

        if ($path === 'profile') {
            if ($user === null) {
                return $this->redirect('/login');
            }
            $isOwn = true;

            if ($user->getIsAdmin()) {
                $postsTmp = TmpPost::find()
                    ->all();
                $complaints = Complaint::find()
                    ->all();
            } else {
                $postsTmp = TmpPost::find()
                    ->byAuthor($user->getUsername())
                    ->all();
                $complaints = Complaint::find()
                    ->bySenderId($user->getId())
                    ->all();
            }
        } else {
            if ($id === $user->getId()) {
                return $this->redirect('/profile');
            }

            if ($id < 1) {
                throw new NotFoundHttpException();
            }

            $user = User::find()
                ->byId($id)
                ->one();

            if ($user === null) {
                throw new NotFoundHttpException();
            }

            $visitor = Yii::$app->user->getIdentity();
            $isOwn = false;
            $tab = 'overview';
        }

        $posts = Post::find()
            ->byAuthor($user->getUsername())
            ->orderDescById()
            ->all();
        $statistics = Statistic::find()
            ->byUsername($user->getUsername())
            ->one();

        return $this->render('profile', [
            'user' => $user,
            'visitor' => $visitor ?? null,
            'posts' => $posts,
            'postsTmp' => $postsTmp ?? null,
            'complaints' => $complaints ?? null,
            'statistics' => $statistics,
            'isOwn' => $isOwn,
            'tab' => $tab,
        ]);
    }
}
