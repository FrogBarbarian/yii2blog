<?php

declare(strict_types=1);

namespace app\controllers;

use app\models\Post;
use app\models\PostInteractionsForm;
use app\models\PostTmp;
use app\models\Statistics;
use app\models\Tag;
use app\models\User;
use Yii;
use yii\db\StaleObjectException;
use yii\web\NotFoundHttpException;
use yii\web\Response;

class AdminController extends AppController
{
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
        $post = PostTmp::find()->byId($id)->one();
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
            $postTmp = PostTmp::find()->byId($id)->one();
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
                    $statistics = Statistics::find()
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

    /**
     * TODO: переделать
     */
    public function actionIndex(string $tab = 'overview'): string
    {
        $user = Yii::$app->user->getIdentity();

        if ($user === null || !$user->getIsAdmin()) {
            throw new NotFoundHttpException();
        }

        $cache = Yii::$app->cache;
        $users = $cache->get('users');

        if ($users === null) {
            $users = User::find()
                ->orderAscById()
                ->all();
            $cache->set('users', $users, 3600);
        }

        $tags = $cache->get('tags');

        if ($tags === null) {
            $tags = Tag::find()->all();
            $cache->set('tags', $tags, 3600);
        }

        return $this->render('panel', [
            'tab' => $tab,
            'users' => $users,
            'tags' => $tags,
        ]);
    }

    /**
     * TODO: переделать
     * @return string  Вид "панель админа". Внутри переправляет на посты пользователей в хранилище.
     * @throws NotFoundHttpException
     */
    public function actionPosts(): string
    {
        if (!Yii::$app->session->has('admin')) {
            throw new NotFoundHttpException();
        }
        $posts = PostTmp::find()->all();

        return $this->render('panel', ['posts' => $posts]);
    }

    /**
     * TODO: переделать
     * @return string Вид "панель админа". Внутри переправляет на жалобы пользователей в хранилище.
     * @throws NotFoundHttpException
     */
    public function actionComplaints(): string
    {
        if (!Yii::$app->session->has('admin')) {
            throw new NotFoundHttpException();
        }

        return $this->render('panel');
    }
}
