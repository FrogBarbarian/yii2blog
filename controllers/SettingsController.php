<?php

declare(strict_types = 1);

namespace app\controllers;

use app\models\ChangePasswordForm;
use app\models\UserForm;
use app\models\User;
use Yii;
use yii\web\NotFoundHttpException;

class SettingsController extends AppController
{
    /**
     * Меняет настройки видимости профиля.
     * @throws NotFoundHttpException
     */
    public function actionChangeVisibility()
    {
        $request = Yii::$app->getRequest();

        if (!$request->getIsAjax() && !isset($_REQUEST['ajax'])) {
            throw new NotFoundHttpException();
        }

        $userId = Yii::$app
            ->user
            ->getId();
        $isVisible = $request->post('ajax')['isVisible'] === 'true';
        $user = User::find()
            ->byId($userId)
            ->one();
        $user
            ->setIsHidden(!$isVisible)
            ->save();
    }

    /**
     * Открывает/закрывает личные сообщения.
     * @throws NotFoundHttpException
     */
    public function actionOpenCloseMessages()
    {
        $request = Yii::$app->getRequest();

        if (!$request->getIsAjax() && !isset($_REQUEST['ajax'])) {
            throw new NotFoundHttpException();
        }

        $userId = Yii::$app
            ->user
            ->getId();
        $isMessagesOpen = $request->post('ajax')['isOpen'] === 'true';
        $user = User::find()
            ->byId($userId)
            ->one();
        $user
            ->setIsMessagesOpen($isMessagesOpen)
            ->save();
    }

    /**
     * Отрисовывает модальное окно изменения пароля.
     * @throws NotFoundHttpException
     */
    public function actionCreatePasswordModal(): string
    {
        $request = Yii::$app->getRequest();

        if (!$request->isAjax) {
            throw new NotFoundHttpException();
        }

        $model = new ChangePasswordForm();

        return $this->renderAjax('//profile/tabs/settings/_password-modal', ['model' => $model]);
    }

    /**
     * Отрисовывает модальное окно изменения почты.
     * @throws NotFoundHttpException
     */
    public function actionCreateEmailModal(): string
    {
        $request = Yii::$app->getRequest();

        if (!$request->isAjax) {
            throw new NotFoundHttpException();
        }

        $model = new UserForm(['scenario' => UserForm::SCENARIO_CHANGE_EMAIL]);

        return $this->renderAjax('//profile/tabs/settings/_email-modal', ['model' => $model]);
    }

    /**
     * Отрисовывает модальное окно изменения имени.
     * @throws NotFoundHttpException
     */
    public function actionCreateUsernameModal(): string
    {
        $request = Yii::$app->getRequest();

        if (!$request->isAjax) {
            throw new NotFoundHttpException();
        }

        $model = new UserForm(['scenario' => UserForm::SCENARIO_CHANGE_USERNAME]);

        return $this->renderAjax('//profile/tabs/settings/_username-modal', ['model' => $model]);
    }
}
