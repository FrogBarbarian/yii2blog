<?php

declare(strict_types=1);

namespace app\controllers;

use app\models\Post;
use app\models\PostInteractionsForm;
use app\models\TmpPost;
use app\models\Statistic;
use src\helpers\Get;
use Yii;
use yii\db\StaleObjectException;
use yii\web\NotFoundHttpException;
use yii\web\Response;

class AdminController extends AppController
{
    /**
     * TODO: переделать
     */
    public function actionIndex(): string
    {
        $user = Yii::$app->user->getIdentity();

        if ($user === null || !$user->getIsAdmin()) {
            throw new NotFoundHttpException();
        }

        return $this->render('overview');
    }

    /**
     * TODO: переделать
     */
    public function actionPosts(): string
    {
        $user = Yii::$app->user->getIdentity();

        if ($user === null || !$user->getIsAdmin()) {
            throw new NotFoundHttpException();
        }

        $tmpPosts = Get::data('tmp_posts');

        return $this->render('posts', ['tmpPosts' => $tmpPosts]);
    }

    /**
     * TODO: COMMENT
     */
    public function actionTags()
    {
        $user = Yii::$app->user->getIdentity();

        if ($user === null || !$user->getIsAdmin()) {
            throw new NotFoundHttpException();
        }

        $tags = Get::data('tags');
        $unusedTags = [];

        foreach ($tags as $tag) {
            if ($tag->getAmountOfUses() === 0) {
                $unusedTags[] = $tag;
            }
        }

        return $this->render('tags', ['unusedTags' => $unusedTags]);
    }

    /**
     * TODO: переделать
     * @return string Вид "панель админа". Внутри переправляет на жалобы пользователей в хранилище.
     * @throws NotFoundHttpException
     */
    public function actionComplaints(): string
    {
        $user = Yii::$app->user->getIdentity();

        if ($user === null || !$user->getIsAdmin()) {
            throw new NotFoundHttpException();
        }

        return $this->render('complaints');
    }

    /**
     * TODO: переделать
     * @return string Вид "панель админа". Внутри переправляет на жалобы пользователей в хранилище.
     * @throws NotFoundHttpException
     */
    public function actionUsers(): string
    {
        $user = Yii::$app->user->getIdentity();

        if ($user === null || !$user->getIsAdmin()) {
            throw new NotFoundHttpException();
        }

        return $this->render('users');
    }
    /**
     * TODO: переделать
     * Отображает пост из временного хранилища по ID из GET (если такой пост найден).
     * @param string $id
     * @return string Вид "пост пользователя".
     * @throws NotFoundHttpException
     */
    public function actionUserPost(string $id = '0'): string
    {
        if (!Yii::$app->session->has('admin') || $id < 1) {
            throw new NotFoundHttpException();
        }
        $post = TmpPost::find()->byId($id)->one();
        if ($post === null) {
            throw new NotFoundHttpException();
        }
        if (!$post->getIsNew()) {
            $originalPost = Post::find()->byId($post->getUpdateId())->one();
        }
        $model = new PostInteractionsForm();

        return $this->render('user-post', [
            'post' => $post,
            'model' => $model,
            'originalPost' => $originalPost ?? null,
        ]);
    }

    /**
     * TODO: переделать
     * Проверяет входные данные и на их основе создает/изменяет существующий пост на основе данных из таблицы хранилища.
     * @return Response
     * @throws NotFoundHttpException
     * @throws \Throwable
     * @throws StaleObjectException
     */
    public function actionConfirm(): Response
    {
        $id = $_POST['PostInteractionsForm']['id'] ?? null;
        if ($id !== null) {
            $postTmp = TmpPost::find()->byId($id)->one();
            if ($postTmp !== null) {
                if ($postTmp->getIsNew()) {
                    $post = new Post();
                    $post
                        ->setTitle($postTmp->getTitle())
                        ->setBody($postTmp->getBody())
                        ->setAuthor($postTmp->getAuthor())
                        ->setTags($postTmp->getTags())
                        ->save();
                    $postTmp->delete();
                    $statistics = Statistic::find()
                        ->byUsername($post->getAuthor())
                        ->one();
                    $statistics
                        ->increasePosts()
                        ->save();
                    //TODO: Публикуем статью, отправляем email создателю о публикации

                    return $this->redirect('/post?id=' . $post->getId());
                }
                $post = Post::find()->byId($postTmp->getUpdateId())->one();
                if ($post !== null) {
                    $post
                        ->setTitle($postTmp->getTitle())
                        ->setBody($postTmp->getBody())
                        ->setTags($postTmp->getTags())
                        ->save();
                    $postTmp->delete();
                    //TODO: Изменяем статью, отправляем email создателю об изменении

                    return $this->redirect('/post?id=' . $post->getId());
                }
            }
        }
        throw new NotFoundHttpException();
    }
}
