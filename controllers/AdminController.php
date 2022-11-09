<?php

declare(strict_types=1);

namespace app\controllers;

use app\models\Message;
use app\models\Post;
use app\models\PostEditorForm;
use app\models\Statistic;
use app\models\Tag;
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
     * @throws \Throwable
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
     * Вкладка с тегами в админ-панели.
     * @throws \Throwable
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
     * @throws \Throwable
     */
    public function actionComplaints(): string
    {
        $user = Yii::$app->user->getIdentity();

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
                default:
                    throw new Exception(
                        "Жалоба с ID {$complaint->getId()} имеет не правильный тип объекта."
                    );
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
     * Страница поста пользователя, для одобрения администратором.
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

        $id = (int)$id;
        $post = TmpPost::find()
            ->byId($id)
            ->one();

        if ($post === null) {
            throw new NotFoundHttpException();
        }

        if (!$post->getIsNew()) {
            $originalPost = Post::find()
                ->byId($post->getUpdateId())
                ->one();
        }

        $model = new PostEditorForm();

        return $this->render('tools/user-post', [
            'post' => $post,
            'model' => $model,
            'originalPost' => $originalPost ?? null,
        ]);
    }

    /**
     * Одобрение публикации поста пользователя.
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
            $statistics = Statistic::find()
                ->byUsername($post->getAuthor())
                ->one();
            $statistics
                ->increasePosts()
                ->save();
        }

        if ($isNew === false) {
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

    public function actionDisapprovePost()
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
        $message = sprintf($format, $comment,$tmpPost->getTitle(), $tmpPost->getBody(), $tmpPost->getTags());
        (new Message())
            ->setSenderUsername($user->getUsername())
            ->setSenderStatus('deleted')
            ->setRecipientUsername($tmpPost->getAuthor())
            ->setSubject('Ваш пост не одобрен')
            ->setContent($message)
            ->save();
        $tmpPost->delete();
    }
}
