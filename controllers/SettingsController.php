<?php

declare(strict_types = 1);

namespace app\controllers;

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
}