<?php

declare(strict_types=1);

namespace app\controllers;

use app\models\ChangePasswordForm;
use app\models\LoginForm;
use app\models\UserForm;
use app\models\Statistic;
use app\models\User;
use Yii;
use yii\base\Exception;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use yii\widgets\ActiveForm;

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

        $model = new UserForm();

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $user = new User();
            $user
                ->setUsername($model->username)
                ->setEmail($model->email)
                ->setPassword($model->password)
                ->save();
            $statistics = new Statistic();
            $statistics
                ->setOwnerId($user->getId())
                ->setOwner($model->username)
                ->save();

            Yii::$app
                ->user
                ->login($user, true ? 3600 * 24 * 30 : 0);

            return $this->redirect('/profile');
        }

        return $this->render('register', ['model' => $model]);
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
     * Меняет пароль.
     * @throws NotFoundHttpException|Exception
     */
    public function actionChangePassword(): Response|string
    {
        $request = Yii::$app->getRequest();

        if (!$request->isAjax) {
            throw new NotFoundHttpException();
        }

        $model = new ChangePasswordForm();

        if ($model->load($request->post()) && $model->validate()) {
            $user = Yii::$app
                ->user
                ->getIdentity();
            $user
                ->setPassword($model->newPassword)
                ->save();

            return $this->asJson(true);
        }

        return $this->asJson(ActiveForm::validate($model));
    }

    /**
     * Меняет почту.
     * @throws NotFoundHttpException
     */
    public function actionChangeEmail(): Response|string
    {
        $request = Yii::$app->getRequest();

        if (!$request->isAjax) {
            throw new NotFoundHttpException();
        }

        $model = new UserForm(['scenario' => UserForm::SCENARIO_CHANGE_EMAIL]);

        if ($model->load($request->post()) && $model->validate()) {
            $user = Yii::$app
                ->user
                ->getIdentity();
            $user
                ->setEmail($model->email)
                ->save();

            return $this->asJson(true);
        }

        return $this->asJson(ActiveForm::validate($model));
    }
}
