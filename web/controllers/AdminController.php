<?php

declare(strict_types=1);

namespace app\controllers;

use app\models\Post;
use app\models\PostInteractionsForm;
use app\models\TmpPost;
use app\models\Statistic;
use app\models\User;
use Psr\SimpleCache\InvalidArgumentException;
use src\helpers\Get;
use Yii;
use yii\db\Exception;
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
     * Вкладка с постами пользователей в админ-панели.
     * @throws \Throwable
     * @throws InvalidArgumentException
     */
    public function actionPosts(): string
    {
        $user = Yii::$app->user->getIdentity();

        if ($user === null || !$user->getIsAdmin()) {
            throw new NotFoundHttpException();
        }

        $tmpPosts = Get::data(
            'tmp_posts',
            'id',
            SORT_ASC,
            false,
        );

        return $this->render('posts', ['tmpPosts' => $tmpPosts]);
    }

    /**
     * Вкладка с тегами в админ-панели.
     * @throws \Throwable
     * @throws InvalidArgumentException
     */
    public function actionTags(string $offset = '0', string $page = '1'): string
    {
        $user = Yii::$app->user->getIdentity();

        if ($user === null || !$user->getIsAdmin()) {
            throw new NotFoundHttpException();
        }

        $tags = Get::data(
            'tags',
            'id',
            SORT_ASC,
            false,
        );
        $unusedTags = [];

        foreach ($tags as $tag) {
            if ($tag->getAmountOfUses() === 0) {
                $unusedTags[] = $tag;
            }
        }

        $curPage = (int)$page;
        $offset = (int)$offset;
        $amountTags = count($tags);
        $pages = $offset < 1 ? 1 : (int)ceil($amountTags / $offset);

        return $this->render('tags', [
            'unusedTags' => $unusedTags,
            'offset' => $offset,
            'curPage' => $curPage,
            'pages' => $pages,
        ]);
    }

    /**
     * Вкладка с жалобами пользователей в админ-панели
     * @throws InvalidArgumentException
     * @throws \Throwable
     */
    public function actionComplaints(): string
    {
        $user = Yii::$app->user->getIdentity();

        if ($user === null || !$user->getIsAdmin()) {
            throw new NotFoundHttpException();
        }

        $complaints = Get::data(
            'complaints',
            'id',
            SORT_ASC,
            false,
        );
        $usersComplaints = [];
        $postsComplaints = [];
        $commentsComplaints = [];

        foreach ($complaints as $complaint) {
            $object = $complaint->getObject();

            switch ($object) {
                case 'user':
                    $usersComplaints[] = $complaint;
                    break;
                case 'post':
                    $postsComplaints[] = $complaint;
                    break;
                case 'comment':
                    $commentsComplaints[] = $complaint;
                    break;
                default:
                    throw new Exception('Не правильный тип объекта.');
            }
        }

        $amountComplaints = count($complaints);

        return $this->render('complaints', [
            'amountComplaints' => $amountComplaints,
            'usersComplaints' => $usersComplaints,
            'postsComplaints' => $postsComplaints,
            'commentsComplaints' => $commentsComplaints,
        ]);
    }

    /**
     * TODO: переделать
     * @return string Вид "панель админа". Внутри переправляет на жалобы пользователей в хранилище.
     * @throws NotFoundHttpException
     */
    public function actionUsers(string $offset = '0', string $page = '1'): string
    {
        $user = Yii::$app->user->getIdentity();

        if ($user === null || !$user->getIsAdmin()) {
            throw new NotFoundHttpException();
        }

        $curPage = (int)$page;
        $offset = (int)$offset;
        $amountUsers = count(User::find()->all());
        $pages = $offset < 1 ? 1 : (int)ceil($amountUsers / $offset);

        return $this->render('users', [
            'offset' => $offset,
            'curPage' => $curPage,
            'pages' => $pages,
        ]);
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
