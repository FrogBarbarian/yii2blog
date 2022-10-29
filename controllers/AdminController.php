<?php

declare(strict_types=1);

namespace app\controllers;

use app\models\Post;
use app\models\PostEditorForm;
use app\models\Statistic;
use app\models\TmpPost;
use app\models\User;
use src\helpers\Get;
use Yii;
use yii\db\Exception;
use yii\db\StaleObjectException;
use yii\web\NotFoundHttpException;
use yii\web\Response;

class AdminController extends AppController
{
    /**
     * Вкладка со статистикой сайта в админ-панели.
     * @throws \Throwable
     */
    public function actionIndex(): string
    {
        $user = Yii::$app->user->getIdentity();

        if ($user === null || !$user->getIsAdmin()) {
            throw new NotFoundHttpException();
        }

        $posts = Get::data(
            'posts',
            'rating',
            SORT_ASC,
            false,
        );
        $amountViews = 0;

        foreach ($posts as $post) {
            $amountViews += $post->getViews();
        }

        $postsAmount = count($posts);
        $highestRatingPost = end($posts);
        $lowestRatingPost = $posts[0];
        $mostViewedPost = Get::data(
            'posts',
            'viewed',
            SORT_DESC,
            false,
        )[0];
        $mostCommentablePost = Get::data(
            'posts',
            'comments_amount',
            SORT_DESC,
            false,
        )[0];
        $users = Get::data(
            'statistics',
            'rating',
            SORT_ASC,
            false);
        $usersAmount = count($users);
        $highestRatingUser = end($users);
        $lowestRatingUser = $users[0];
        $mostPostUser = Get::data(
            'statistics',
            'posts',
            SORT_DESC,
            false)[0];
        $mostCommentUser = Get::data(
            'statistics',
            'comments',
            SORT_DESC,
            false)[0];
        $statsWithLike = Statistic::find()
            ->byLikes()
            ->all();
        $amountLikes = 0;

        foreach ($statsWithLike as $item) {
            $amountLikes += $item->getLikes();
        }

        $statsWithDislike = Statistic::find()
            ->byDislikes()
            ->all();
        $amountDislikes = 0;

        foreach ($statsWithDislike as $item) {
            $amountDislikes += $item->getDislikes();
        }

        $comments = Get::data(
            'comments',
            'rating',
            SORT_ASC,
            false);
        $commentsAmount = count($comments);
        $highestRatingComment = end($comments);
        $lowestRatingComment = $comments[0];

        return $this->render('overview', [
            'postsAmount' => $postsAmount,
            'mostViewedPost' => $mostViewedPost,
            'highestRatingPost' => $highestRatingPost,
            'lowestRatingPost' => $lowestRatingPost,
            'mostCommentablePost' => $mostCommentablePost,
            'usersAmount' => $usersAmount,
            'mostPostUser' => $mostPostUser,
            'mostCommentUser' => $mostCommentUser,
            'highestRatingUser' => $highestRatingUser,
            'lowestRatingUser' => $lowestRatingUser,
            'commentsAmount' => $commentsAmount,
            'highestRatingComment' => $highestRatingComment,
            'lowestRatingComment' => $lowestRatingComment,
            'amountLikes' => $amountLikes,
            'amountDislikes' => $amountDislikes,
            'amountViews' => $amountViews,
        ]);
    }

    /**
     * Вкладка с постами пользователей в админ-панели.
     * @throws \Throwable
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
     */
    public function actionTags(string $offset = '0', string $page = '1', string $sortParam = 'id', string $sortOrder = '4'): string
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
        $sortOrder = (int)$sortOrder;
        $amountTags = count($tags);
        $pages = $offset < 1 ? 1 : (int)ceil($amountTags / $offset);

        return $this->render('tags', [
            'unusedTags' => $unusedTags,
            'offset' => $offset,
//    "yiisoft/yii2-bootstrap": "*"
            'curPage' => $curPage,
            'pages' => $pages,
            'sortParam' => $sortParam,
            'sortOrder' => $sortOrder,
        ]);
    }

    /**
     * Вкладка с жалобами пользователей в админ-панели.
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
     * Вклада с пользователями в админ-панели.
     * @throws \Throwable
     */
    public function actionUsers(string $offset = '0', string $page = '1', string $sortParam = 'id', string $sortOrder = '4'): string
    {
        $user = Yii::$app->user->getIdentity();

        if ($user === null || !$user->getIsAdmin()) {
            throw new NotFoundHttpException();
        }

        $curPage = (int)$page;
        $offset = (int)$offset;
        $sortOrder = (int)$sortOrder;
        $amountUsers = count(User::find()->all());
        $pages = $offset < 1 ? 1 : (int)ceil($amountUsers / $offset);

        return $this->render('users', [
            'offset' => $offset,
            'curPage' => $curPage,
            'pages' => $pages,
            'sortParam' => $sortParam,
            'sortOrder' => $sortOrder,
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
        $model = new PostEditorForm();

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
        $id = $_POST['PostEditorForm']['id'] ?? null;
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
