<?php

declare(strict_types = 1);

namespace app\controllers;

use app\models\ChangePasswordForm;
use app\models\User;
use Yii;
use yii\web\NotFoundHttpException;
use yii\web\Response;

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

    public function actionCreatePasswordModal(): string
    {
        $request = Yii::$app->getRequest();

        if (!$request->isAjax) {
            throw new NotFoundHttpException();
        }

        $model = new ChangePasswordForm();

        return $this->renderAjax('//profile/tabs/settings/_password-modal', ['model' => $model]);
    }
}
