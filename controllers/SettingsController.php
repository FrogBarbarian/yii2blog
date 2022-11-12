<?php

declare(strict_types = 1);

namespace app\controllers;

use app\models\ChangePasswordForm;
use app\models\UserForm;
use app\models\User;
use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

/**
 * Контроллер настроек пользователя.
 */
class SettingsController extends Controller
{
    /**
     * Меняет настройки видимости профиля.
     *
     * @throws NotFoundHttpException
     */
    public function actionChangeVisibility(): void
    {
        $request = Yii::$app->getRequest();

        if (!$request->getIsAjax()) {
            throw new NotFoundHttpException();
        }

        $userId = Yii::$app
            ->user
            ->getId();
        $isVisible = $request->post('isVisible') === 'true';
        $user = User::findOne($userId);
        $user
            ->setIsHidden(!$isVisible)
            ->save();
    }

    /**
     * Открывает/закрывает личные сообщения.
     *
     * @throws NotFoundHttpException
     */
    public function actionOpenCloseMessages(): void
    {
        $request = Yii::$app->getRequest();

        if (!$request->getIsAjax()) {
            throw new NotFoundHttpException();
        }

        $userId = Yii::$app
            ->user
            ->getId();
        $isMessagesOpen = $request->post('isOpen') === 'true';
        $user = User::findOne($userId);
        $user
            ->setIsMessagesOpen($isMessagesOpen)
            ->save();
    }

    /**
     * Отрисовывает модальное окно изменения пароля.
     *
     * @throws NotFoundHttpException
     */
    public function actionCreatePasswordModalWindow(): string
    {
        $request = Yii::$app->getRequest();

        if (!$request->isAjax) {
            throw new NotFoundHttpException();
        }

        $model = new ChangePasswordForm();

        return $this->renderAjax('/profile/tabs/settings/_password-modal', ['model' => $model]);
    }

    /**
     * Отрисовывает модальное окно изменения почты.
     *
     * @throws NotFoundHttpException
     */
    public function actionCreateEmailModalWindow(): string
    {
        $request = Yii::$app->getRequest();

        if (!$request->isAjax) {
            throw new NotFoundHttpException();
        }

        $model = new UserForm(['scenario' => UserForm::SCENARIO_CHANGE_EMAIL]);

        return $this->renderAjax('/profile/tabs/settings/_email-modal', ['model' => $model]);
    }
}
