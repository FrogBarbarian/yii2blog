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
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use yii\widgets\ActiveForm;

/**
 *  Контроллер пользователя.
 */
class UserController extends Controller
{
    /**
     * Страница регистрации.
     *
     * @throws Exception
     */
    public function actionRegister(): Response|string
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $request = Yii::$app->getRequest();
        $model = new UserForm();

        if ($request->isAjax || $request->getIsPost()) {
            $model->load($request->post());
        }

        if ($request->isAjax && isset($_REQUEST['ajax'])) {
            return $this->asJson(ActiveForm::validate($model));
        }

        if ($request->getIsPost() && $model->validate()) {
            $user = new User();
            $user
                ->setUsername($model->username)
                ->setEmail($model->email)
                ->setPassword($model->password)
                ->save();
            (new Statistic())
                ->setOwnerId($user->getId())
                ->setOwner($model->username)
                ->save();

            Yii::$app
                ->mailer
                ->compose('register')
                ->setFrom(NO_REPLY_MAIL)
                ->setTo($model->email)
                ->setSubject('Спасибо за регистрацию')
                ->send();

            Yii::$app
                ->user
                ->login($user, true ? 3600 * 24 * 30 : 0);

            return $this->redirect('/profile');
        }

        return $this->render('register', ['model' => $model]);
    }

    /**
     * Разлогинивание.
     */
    public function actionLogout(): Response
    {
        Yii::$app
            ->user
            ->logout();

        return $this->goHome();
    }

    /**
     * Страница входа.
     */
    public function actionLogin(): Response|string
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $user = User::find()
                ->byEmail($model->email)
                ->one();
            Yii::$app
                ->user
                ->login($user, $model->rememberMe ? 3600 * 24 * 30 : 0);

            return $this->redirect('/profile');
        }

        return $this->render('login', ['model' => $model]);
    }

    /**
     * Меняет пароль.
     *
     * @throws NotFoundHttpException
     * @throws Exception
     */
    public function actionChangePassword(): Response|string
    {
        $request = Yii::$app->getRequest();

        if (!$request->isAjax) {
            throw new NotFoundHttpException();
        }

        $model = new ChangePasswordForm();
        $model->load($request->post());

        if (isset($_REQUEST['ajax'])) {
            return $this->asJson(ActiveForm::validate($model));
        }

        if ($model->validate()) {
            $user = Yii::$app
                ->user
                ->getIdentity();
            $user
                ->setPassword($model->newPassword)
                ->save();

            return $this->asJson(true);
        }

        return $this->asJson(false);
    }

    /**
     * Меняет почту.
     *
     * @throws NotFoundHttpException
     */
    public function actionChangeEmail(): Response|string
    {
        $request = Yii::$app->getRequest();

        if (!$request->isAjax) {
            throw new NotFoundHttpException();
        }

        $model = new UserForm(['scenario' => UserForm::SCENARIO_CHANGE_EMAIL]);
        $model->load($request->post());

        if (isset($_REQUEST['ajax'])) {
            return $this->asJson(ActiveForm::validate($model));
        }

        if ($model->validate()) {
            $user = Yii::$app
                ->user
                ->getIdentity();
            $user
                ->setEmail($model->email)
                ->save();

            return $this->asJson(true);
        }

        return $this->asJson(false);
    }

    /**
     * Страница восстановления пароля.
     */
    public function actionRestore(): Response|string
    {
        $user = Yii::$app
            ->user
            ->getIdentity();
        $model = new UserForm(['scenario' => UserForm::SCENARIO_RESTORE_PASSWORD]);
        $request = Yii::$app->getRequest();

        if ($request->getIsAjax()) {
            $model->load($request->post());

            if (isset($_REQUEST['ajax'])) {
                return $this->asJson(ActiveForm::validate($model));
            }

            if ($model->validate()) {
                $user = User::find()
                    ->byEmail($model->email)
                    ->one()
                    ->generatePasswordResetToken()
                    ->save();
                $token = $user->getPasswordResetToken();
            Yii::$app
                ->mailer
                ->compose('reset-password', ['token' => $token])
                ->setFrom(NO_REPLY_MAIL)
                ->setTo($model->email)
                ->setSubject('Восстановление пароля')
                ->send();

                return $this->asJson(true);
            }

            return $this->asJson(false);
        }

        $email = $user === null ? '' : $user->getEmail();

        return $this->render('restore', ['model' => $model, 'email' => $email]);
    }

    /**
     * Страница создания нового пароля.
     *
     * @throws Exception
     */
    public function actionNewPassword(string $token = ''): Response|string
    {
        $user = User::findByPasswordResetToken($token);
        $model = new UserForm(['scenario' => UserForm::SCENARIO_NEW_PASSWORD]);
        $request = Yii::$app->getRequest();

        if ($request->getIsAjax()) {
            $model->load($request->post());

            if (isset($_REQUEST['ajax'])) {
                return $this->asJson(ActiveForm::validate($model));
            }

            if ($model->validate()) {
                $user
                    ->setPassword($model->password)
                    ->removePasswordResetToken()
                    ->save();

                return $this->asJson(true);
            }

            return $this->asJson(false);
        }

        $tokenIsValid = $user !== null;

        return $this->render('new-password', ['model' => $model, 'tokenIsValid' => $tokenIsValid]);
    }
}
