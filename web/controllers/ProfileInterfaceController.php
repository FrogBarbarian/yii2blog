<?php

declare(strict_types=1);

namespace app\controllers;

use app\models\User;
use Yii;
use yii\web\NotFoundHttpException;
use yii\web\Response;

class ProfileInterfaceController extends AppController
{
    /**
     * Меняет права пользователя на написание комментариев.
     * @throws NotFoundHttpException
     */
    public function actionSetCommentsPermissions(): Response
    {
        $request = Yii::$app->getRequest();

        if (!$request->isAjax) {
            throw new NotFoundHttpException();
        }

        $userId = $request->post('id');
        $user = User::find()
            ->byId($userId)
            ->one();
        $user
            ->setCanComment(!$user->getCanComment())
            ->save();

        return $this->asJson($user->getCanComment());
    }

    /**
     * Меняет права пользователя на создание постов.
     * @throws NotFoundHttpException
     */
    public function actionSetCreatePostsPermissions(): Response
    {
        $request = Yii::$app->getRequest();

        if (!$request->isAjax) {
            throw new NotFoundHttpException();
        }

        $userId = $request->post('id');
        $user = User::find()
            ->byId($userId)
            ->one();
        $user
            ->setCanWritePosts(!$user->getCanWritePosts())
            ->save();

        return $this->asJson($user->getCanWritePosts());
    }

    /**
     * Меняет права пользователя на использование ЛС.
     * @throws NotFoundHttpException
     */
    public function actionSetPrivateMessagesPermissions(): Response
    {
        $request = Yii::$app->getRequest();

        if (!$request->isAjax) {
            throw new NotFoundHttpException();
        }

        $userId = $request->post('id');
        $user = User::find()
            ->byId($userId)
            ->one();
        $user
            ->setCanWriteMessages(!$user->getCanWriteMessages())
            ->save();

        return $this->asJson($user->getCanWriteMessages());
    }

    /**
     * Делает пользователя админом.
     * @throws NotFoundHttpException
     */
    public function actionSetUserAdmin()
    {
        $request = Yii::$app->getRequest();

        if (!$request->isAjax) {
            throw new NotFoundHttpException();
        }

        $userId = $request->post('id');
        $user = User::find()
            ->byId($userId)
            ->one();
        $user
            ->setCanWriteMessages(true)
            ->setCanWritePosts(true)
            ->setCanComment(true)
            ->setIsAdmin(true)
            ->save();
    }
}
