<?php

declare(strict_types=1);

namespace app\controllers;

use app\models\User;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use Yii;

/**
 * Обрабатывает ajax запросы со страницы профиля.
 */
class ProfileAjaxController extends Controller
{
    /** Функционал администратора */

    /**
     * Меняет права пользователя на написание комментариев.
     *
     * @throws NotFoundHttpException
     */
    public function actionSetCommentsPermissions(): Response
    {
        $request = Yii::$app->getRequest();

        if (!$request->isAjax) {
            throw new NotFoundHttpException();
        }

        $username = $request->post('username');
        $user = User::find()
            ->byUsername($username)
            ->one();
        $user
            ->setCanComment(!$user->getCanComment())
            ->save();

        return $this->asJson($user->getCanComment());
    }

    /**
     * Меняет права пользователя на создание постов.
     *
     * @throws NotFoundHttpException
     */
    public function actionSetCreatePostsPermissions(): Response
    {
        $request = Yii::$app->getRequest();

        if (!$request->isAjax) {
            throw new NotFoundHttpException();
        }

        $username = $request->post('username');
        $user = User::find()
            ->byUsername($username)
            ->one();
        $user
            ->setCanWritePosts(!$user->getCanWritePosts())
            ->save();

        return $this->asJson($user->getCanWritePosts());
    }

    /**
     * Меняет права пользователя на использование личных сообщений.
     *
     * @throws NotFoundHttpException
     */
    public function actionSetPrivateMessagesPermissions(): Response
    {
        $request = Yii::$app->getRequest();

        if (!$request->isAjax) {
            throw new NotFoundHttpException();
        }

        $username = $request->post('username');
        $user = User::find()
            ->byUsername($username)
            ->one();
        $user
            ->setCanWriteMessages(!$user->getCanWriteMessages())
            ->save();

        return $this->asJson($user->getCanWriteMessages());
    }

    /**
     * Отрисовывает модальное окно для подтверждения назначения пользователя администратором.
     *
     * @throws NotFoundHttpException
     */
    public function actionCreateUserToAdminWindow(): string
    {
        $request = Yii::$app->getRequest();

        if (!$request->isAjax) {
            throw new NotFoundHttpException();
        }

        $username = $request->post('username');

        return $this->renderAjax('//ui/uta-modal', ['username' => $username]);
    }

    /**
     * Делает пользователя администратором.
     *
     * @throws NotFoundHttpException
     */
    public function actionSetUserAdmin(): void
    {
        $request = Yii::$app->getRequest();

        if (!$request->isAjax) {
            throw new NotFoundHttpException();
        }

        $username = $request->post('username');
        User::find()
            ->byUsername($username)
            ->one()
            ->setCanWriteMessages(true)
            ->setCanWritePosts(true)
            ->setCanComment(true)
            ->setIsAdmin(true)
            ->save();
    }

    /**
     * Банит/разбанивает пользователя.
     *
     * @throws NotFoundHttpException
     */
    public function actionSetUserBan()
    {
        $request = Yii::$app->getRequest();

        if (!$request->isAjax) {
            throw new NotFoundHttpException();
        }

        $username = $request->post('username');
        $user = User::find()
            ->byUsername($username)
            ->one();
        $isBanned = !$user->getIsBanned();
        $status = $isBanned ? USER::STATUS_BANNED : USER::STATUS_ACTIVE;
        $user
            ->setIsBanned($isBanned)
            ->setStatus($status)
            ->save();
    }
}
