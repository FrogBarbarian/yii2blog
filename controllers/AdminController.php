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
use yii\web\Controller;
use yii\web\NotFoundHttpException;

/**
 * Контроллер панели администратора.
 */
class AdminController extends Controller
{
    /**
     * Вкладка со статистикой сайта в админ-панели.
     *
     * @throws NotFoundHttpException
     */
    public function actionIndex(): string
    {
        $user = Yii::$app
            ->user
            ->getIdentity();

        if ($user === null || !$user->getIsAdmin()) {
            throw new NotFoundHttpException();
        }

        $posts = Get::data('posts', SORT_ASC, 'rating');
        $amountViews = 0;

        foreach ($posts as $post) {
            $amountViews += $post->getViews();
        }

        $postsAmount = count($posts);
        $highestRatingPost = end($posts);
        $lowestRatingPost = $posts[0];
        $mostViewedPost = Get::data('posts', SORT_DESC, 'viewed')[0];
        $mostCommentablePost = Get::data('posts', SORT_DESC, 'comments_amount')[0];
        $users = Get::data('statistics', SORT_ASC, 'rating');
        $usersAmount = count($users);
        $highestRatingUser = end($users);
        $lowestRatingUser = $users[0];
        $mostPostUser = Get::data('statistics', SORT_DESC, 'posts')[0];
        $mostCommentUser = Get::data('statistics', SORT_DESC, 'comments')[0];
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

        $comments = Get::data('comments', SORT_ASC, 'rating');
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
     *
     * @throws NotFoundHttpException
     */
    public function actionPosts(): string
    {
        $user = Yii::$app
            ->user
            ->getIdentity();

        if ($user === null || !$user->getIsAdmin()) {
            throw new NotFoundHttpException();
        }

        $tmpPosts = Get::data('tmp_posts');

        return $this->render('posts', ['tmpPosts' => $tmpPosts]);
    }

    /**
     * Вкладка с тегами в админ-панели.
     *
     * @throws NotFoundHttpException
     */
    public function actionTags(string $offset = '0', string $page = '1', string $sortParam = 'id', string $sortOrder = '4'): string
    {
        $user = Yii::$app
            ->user
            ->getIdentity();

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

        $curPage = (int)$page;
        $offset = (int)$offset;
        $sortOrder = (int)$sortOrder;
        $amountTags = count($tags);
        $pages = $offset < 1 ? 1 : (int)ceil($amountTags / $offset);

        return $this->render('tags', [
            'unusedTags' => $unusedTags,
            'offset' => $offset,
            'curPage' => $curPage,
            'pages' => $pages,
            'sortParam' => $sortParam,
            'sortOrder' => $sortOrder,
        ]);
    }

    /**
     * Вкладка с жалобами пользователей в админ-панели.
     *
     * @throws NotFoundHttpException
     */
    public function actionComplaints(): string
    {
        $user = Yii::$app
            ->user
            ->getIdentity();

        if ($user === null || !$user->getIsAdmin()) {
            throw new NotFoundHttpException();
        }

        $complaints = Get::data('complaints');
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
     * Вкладка с пользователями в админ-панели.
     *
     * @throws NotFoundHttpException
     */
    public function actionUsers(string $offset = '0', string $page = '1', string $sortParam = 'id', string $sortOrder = '4'): string
    {
        $user = Yii::$app
            ->user
            ->getIdentity();

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
     * Страница поста пользователя, для одобрения администратором.
     *
     * @throws NotFoundHttpException
     */
    public function actionUserPost(string $id = '0'): string
    {
        $user = Yii::$app
            ->user
            ->getIdentity();

        if ($user === null || !$user->getIsAdmin()) {
            throw new NotFoundHttpException();
        }

        $post = TmpPost::findOne($id);

        if ($post === null) {
            throw new NotFoundHttpException();
        }

        if (!$post->getIsNew()) {
            $originalPost = Post::findOne($post->getUpdateId());
        }

        $model = new PostEditorForm();

        return $this->render('tools/user-post', [
            'post' => $post,
            'model' => $model,
            'originalPost' => $originalPost ?? null,
        ]);
    }
}
