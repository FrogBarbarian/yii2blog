<?php

namespace app\controllers;

use app\models\Post;
use app\models\PostInteractionsForm;
use app\models\PostTmp;
use app\models\Statistics;
use app\models\User;
use Yii;
use yii\db\StaleObjectException;
use yii\web\NotFoundHttpException;
use yii\web\Response;

class AdminController extends AppController
{
    /**
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
                        ->byLogin($post->getAuthor())
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
     * @return string Вид "панель админа". Обзорная страница.
     * @throws NotFoundHttpException
     */
    public function actionIndex(): string
    {
        if (!Yii::$app->session->has('admin')) {
            throw new NotFoundHttpException();
        }
        if (isset($_POST['changeStatus'])) {
            $user = User::find()->byId($_POST['id'])->one();
            $user
                ->setIsAdmin(!$_POST['isAdmin'])
                ->save();
        }
        $users = User::find()->orderAscById()->all();

        return $this->render('panel', ['users' => $users]);
    }

    /**
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
