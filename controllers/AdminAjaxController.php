<?php

declare(strict_types=1);

namespace app\controllers;

use app\models\Complaint;
use app\models\Message;
use app\models\Post;
use app\models\Statistic;
use app\models\Tag;
use app\models\TmpPost;
use yii\db\StaleObjectException;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use Yii;

/**
 * Обрабатывает ajax запросы c панели администратора.
 */
class AdminAjaxController extends Controller
{
    /**
     * Одобрение публикации поста пользователя.
     *
     * @throws NotFoundHttpException
     * @throws StaleObjectException
     * @throws \Throwable
     */
    public function actionApprovePost(): Response
    {
        $request = Yii::$app->getRequest();

        if (!$request->isAjax) {
            throw new NotFoundHttpException();
        }

        $user = Yii::$app
            ->user
            ->getIdentity();
        $id = $request->post('id');
        $tmpPost = TmpPost::findOne($id);
        $isNew = $tmpPost->getIsNew();

        if ($isNew === true) {
            $post = Post::find()
                ->byTitle($tmpPost->getTitle())
                ->one();

            if ($post !== null) {
                return $this->asJson('Пост с таким названием уже существует');
            }

            $post = new Post();
            $post
                ->setTitle($tmpPost->getTitle())
                ->setBody($tmpPost->getBody())
                ->setAuthor($tmpPost->getAuthor())
                ->setAuthorId($tmpPost->getAuthorId())
                ->setTags($tmpPost->getTags())
                ->save();
            Tag::checkWhenCreatePost($post->getTagsArray());
            Statistic::find()
                ->byUsername($post->getAuthor())
                ->one()
                ->increasePosts()
                ->save();
        } else {
            $post = Post::findOne($tmpPost->getUpdateId());

            if ($post === null) {
                return $this->asJson('Оригинальный пост не найден');
            }

            $post
                ->setTitle($tmpPost->getTitle())
                ->setBody($tmpPost->getBody())
                ->setTags($tmpPost->getTags())
                ->save();
            Tag::checkWhenUpdatePost($tmpPost->getOldTagsArray(), $tmpPost->getTagsArray());
        }

        (new Message())
            ->setSenderUsername($user->getUsername())
            ->setSenderStatus('deleted')
            ->setRecipientUsername($post->getAuthor())
            ->setSubject('Ваш пост одобрен')
            ->setContent("Пост с названием '{$post->getTitle()}' одобрен и опубликован")
            ->save();
        $tmpPost->delete();

        return $this->asJson(true);
    }

    /**
     * Отказ в одобрении поста пользователя.
     *
     * @throws NotFoundHttpException
     * @throws StaleObjectException
     * @throws \Throwable
     */
    public function actionDisapprovePost(): void
    {
        $request = Yii::$app->getRequest();

        if (!$request->isAjax) {
            throw new NotFoundHttpException();
        }

        $user = Yii::$app
            ->user
            ->getIdentity();
        $id = $request->post('id');
        $comment = $request->post('comment');
        $tmpPost = TmpPost::findOne($id);
        $format = '%s<hr><h5>%s</h5>%s<br>Использованные теги: %s';
        $message = sprintf($format, $comment, $tmpPost->getTitle(), $tmpPost->getBody(), $tmpPost->getTags());
        (new Message())
            ->setSenderUsername($user->getUsername())
            ->setSenderStatus('deleted')
            ->setRecipientUsername($tmpPost->getAuthor())
            ->setSubject('Ваш пост не одобрен')
            ->setContent($message)
            ->save();
        $tmpPost->delete();
    }

    /**
     * Получает объекты для админ-панели.
     *
     * @throws NotFoundHttpException
     */
    public function actionGetObjects(string $model, string $offset, string $page, string $sortParam, string $sortOrder): Response
    {
        $request = Yii::$app->getRequest();

        if (!$request->isAjax) {
            throw new NotFoundHttpException();
        }

        $page = (int)$page;
        $offset = (int)$offset;
        $sortOrder = (int)$sortOrder;
        $objects = ("app\models\\$model")::find()
            ->orderBy([$sortParam => $sortOrder])
            ->offset($offset * ($page - 1))
            ->limit($offset)
            ->all();

        return $this->asJson($objects);
    }

    /**
     * Удаляет тег.
     *
     * @throws NotFoundHttpException
     * @throws StaleObjectException
     * @throws \Throwable
     */
    public function actionDeleteTag(string $id): void
    {
        $request = Yii::$app->getRequest();

        if (!$request->isAjax) {
            throw new NotFoundHttpException();
        }

        Tag::findOne($id)->delete();
    }

    /**
     * Удаляет жалобу.
     *
     * @throws NotFoundHttpException
     * @throws StaleObjectException
     * @throws \Throwable
     */
    public function actionDeleteComplaint(string $id): void
    {
        $request = Yii::$app->getRequest();

        if (!$request->isAjax) {
            throw new NotFoundHttpException();
        }

        $user = Yii::$app
            ->user
            ->getIdentity();
        $complaint = Complaint::findOne($id);
        $format = '<a href="%s">Жалоба</a> рассмотрена, меры приняты';
        $link = match ($complaint->getObject()) {
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
     * Удаляет неиспользуемые изображения из папки с загрузки.
     *
     * @throws NotFoundHttpException
     */
    public function actionClearImages(): void
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
