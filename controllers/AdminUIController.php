<?php

declare(strict_types=1);

namespace app\controllers;

use app\models\Complaint;
use app\models\Message;
use app\models\Post;
use app\models\Tag;
use app\models\TmpPost;
use app\models\User;
use Yii;
use yii\db\StaleObjectException;
use yii\web\NotFoundHttpException;
use yii\web\Response;

class AdminUIController extends AppController
{
    /**
     * Удаляет тег.
     * @throws \Throwable
     */
    public function actionDeleteTag(string $id)
    {
        if (!Yii::$app->request->isAjax) {
            throw new NotFoundHttpException();
        }

        $id = (int)$id;
        Tag::find()
            ->byId($id)
            ->one()
            ->delete();
        $cache = Yii::$app->cache;
        $cache->delete('tags');
    }

    /**
     * Удаляет жалобу.
     * @throws \Throwable
     * @throws StaleObjectException
     */
    public function actionDeleteComplaint(string $id)
    {
        if (!Yii::$app->request->isAjax) {
            throw new NotFoundHttpException();
        }

        $user = Yii::$app
            ->user
            ->getIdentity();
        $id = (int)$id;
        $complaint = Complaint::find()
            ->byId($id)
            ->one();
        $format = '<a href="%s">Жалоба</a> рассмотрена, меры приняты';
        $link = match($complaint->getObject()) {
            'user' => "/profile/index?id={$complaint->getObjectId()}",
            'post' => "/post?id={$complaint->getObjectId()}",
            'comment' => "/comment?id={$complaint->getObjectId()}",
        };
        (new Message())
            ->setSenderUsername($user->getUsername())
            ->setRecipientUsername($complaint->getSenderUsername())
            ->setSubject('Жалоба закрыта')
            ->setContent(sprintf($format, $link))
            ->save();
        $complaint->delete();
    }

    /**
     * Получает объекты для админ-панели.
     * @throws NotFoundHttpException
     */
    public function actionGetObjects(string $model, string $offset, string $page, string $sortParam, string $sortOrder): Response
    {
        if (!Yii::$app->request->isAjax) {
            throw new NotFoundHttpException();
        }

        $page = (int)$page;
        $offset = (int)$offset;
        $sortOrder = (int)$sortOrder;
        $users = ("app\models\\$model")::find()
            ->orderBy([$sortParam => $sortOrder])
            ->offset($offset * ($page - 1))
            ->limit($offset)
            ->all();

        return $this->asJson($users);
    }

    /**
     * Меняет права пользователя на написание комментариев.
     * @throws NotFoundHttpException
     */
    public function actionSetCommentsPermissions(): Response
    {
        $request = Yii::$app->getRequest();

        if (!$request->isAjax && !isset($_REQUEST['ajax'])) {
            throw new NotFoundHttpException();
        }

        $username = $request->post('ajax')['username'];
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
     * @throws NotFoundHttpException
     */
    public function actionSetCreatePostsPermissions(): Response
    {
        $request = Yii::$app->getRequest();

        if (!$request->isAjax && !isset($_REQUEST['ajax'])) {
            throw new NotFoundHttpException();
        }

        $username = $request->post('ajax')['username'];
        $user = User::find()
            ->byUsername($username)
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

        if (!$request->isAjax && !isset($_REQUEST['ajax'])) {
            throw new NotFoundHttpException();
        }

        $username = $request->post('ajax')['username'];
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
     * @throws NotFoundHttpException
     */
    public function actionCreateUserToAdminWindow(): string
    {
        $request = Yii::$app->getRequest();

        if (!$request->isAjax) {
            throw new NotFoundHttpException();
        }

        $username = $request->post('ajax')['username'];

        return $this->renderAjax('//u-i/uta-modal', ['username' => $username]);
    }

    /**
     * Делает пользователя администратором.
     * @throws NotFoundHttpException
     */
    public function actionSetUserAdmin()
    {
        $request = Yii::$app->getRequest();

        if (!$request->isAjax && !isset($_REQUEST['ajax'])) {
            throw new NotFoundHttpException();
        }

        $username = $request->post('ajax')['username'];
        $user = User::find()
            ->byUsername($username)
            ->one();
        $user
            ->setCanWriteMessages(true)
            ->setCanWritePosts(true)
            ->setCanComment(true)
            ->setIsAdmin(true)
            ->save();
    }

    /**
     * Банит/разбанивает пользователя.
     * @throws NotFoundHttpException
     */
    public function actionSetUserBan()
    {
        $request = Yii::$app->getRequest();

        if (!$request->isAjax && !isset($_REQUEST['ajax'])) {
            throw new NotFoundHttpException();
        }

        $username = $request->post('ajax')['username'];
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

    /**
     * Удаляет неиспользуемые изображения из папки с загрузки.
     * @throws NotFoundHttpException
     */
    public function actionClearImages()
    {
        $request = Yii::$app->getRequest();

        if (!$request->isAjax) {
            throw new NotFoundHttpException();
        }

        $dir = $_SERVER['DOCUMENT_ROOT'] . '/uploads';
        $files = scandir($dir);

        foreach ($files as $file) {
            if ($file === '.' || $file === '..') {
                continue;
            }

            $post = Post::find()
                ->where(['ILIKE', 'body', $file])
                ->one();
            $tmpPost = TmpPost::find()
                ->where(['ILIKE', 'body', $file])
                ->one();

            if ($post === null && $tmpPost === null) {
                unlink("$dir/$file");
            }
        }
    }
}
