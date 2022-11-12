<?php

declare(strict_types=1);

namespace app\controllers;

use app\models\Comment;
use app\models\CommentForm;
use app\models\Post;
use app\models\Statistic;
use app\models\User;
use Yii;
use yii\base\InvalidConfigException;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

/**
 * Контроллер для отображения постов.
 */
class PostController extends Controller
{
    /**
     * Главная страница с постами.
     *
     * @throws NotFoundHttpException
     */
    public function actionIndex(string $page = '1', string $search = null): string
    {
        if ($search === '') {
            $search = null;
        }

        $curPage = (int)$page;

        if ($curPage < 1) {
            throw new NotFoundHttpException();
        }


        if ($search !== null) {
            $posts = Post::find()
                ->postHasWords($search)
                ->orderDescById()
                ->offset(($curPage - 1) * POSTS_ON_PAGE)
                ->limit(POSTS_ON_PAGE)
                ->all();
            $postsAmount = count(Post::find()
                ->postHasWords($search)
                ->asArray()
                ->all());
            $pages = (int)(ceil($postsAmount / POSTS_ON_PAGE));
            $message = $posts
                ? "Результат поиска по фразе '$search'"
                : "К сожалению, по запросу '$search' ничего не найдено. <a class='complaint-link' href='/'>Сбросить результат?</a>";
            Yii::$app
                ->session
                ->setFlash('messageForIndex', $message);
        } else {
            $posts = Post::find()
                ->orderDescById()
                ->offset(($curPage - 1) * POSTS_ON_PAGE)
                ->limit(POSTS_ON_PAGE)
                ->all();
            $pages = (int)(ceil(Post::find()->count() / POSTS_ON_PAGE));
        }

        if (!$posts && $search === null || $search !== null && $curPage > 1 && $curPage > $pages) {
            throw new NotFoundHttpException();
        }

        $user = Yii::$app
            ->user
            ->getIdentity();

        return $this->render('index', [
            'posts' => $posts,
            'pages' => $pages,
            'curPage' => $curPage,
            'search' => $search,
            'user' => $user,
        ]);
    }

    /**
     * Страница поста.
     *
     * @throws NotFoundHttpException
     */
    public function actionPost(string $id = '0'): string
    {
        $visitorIsLogin = !Yii::$app
            ->user
            ->isGuest;
        $id = (int)$id;

        if ($id > 0) {
            $post = Post::findOne($id);

            if ($post !== null) {
                $commentForm = new CommentForm();
                Statistic::find()
                    ->byUsername($post->getAuthor())
                    ->one()
                    ->increaseViews()
                    ->save();
                $post
                    ->increasePostViews()
                    ->save();
                $owner = User::find()
                    ->byUsername($post->getAuthor())
                    ->one();
                $comments = Comment::find()
                    ->byPostId($post->getId())
                    ->orderAscById()
                    ->all();
                $user = Yii::$app
                    ->user
                    ->getIdentity();

                return $this->render('post', [
                    'post' => $post,
                    'user' => $user,
                    'owner' => $owner,
                    'comments' => $comments,
                    'commentForm' => $commentForm,
                    'visitorIsLogin' => $visitorIsLogin,
                ]);
            }
        }

        throw new NotFoundHttpException();
    }


    /**
     * Главная страница с постами с определенным тегом.
     *
     * @throws NotFoundHttpException
     * @throws InvalidConfigException
     */
    public function actionTag(string $page = '1'): string
    {
        $path = Yii::$app
            ->getRequest()
            ->getPathInfo();

        if ($path === 'tag') {
            throw new NotFoundHttpException();
        }

        $tag = ltrim(strrchr($path, '/'), '/');
        $posts = Post::find()
            ->byTag($tag)
            ->all();

        $pages = (int)(ceil(count($posts) / POSTS_ON_PAGE));
        $curPage = (int)$page;
        $posts = Post::find()
            ->byTag($tag)
            ->orderDescById()
            ->offset(($page - 1) * POSTS_ON_PAGE)
            ->limit(POSTS_ON_PAGE)
            ->all();
        $user = Yii::$app
            ->user
            ->getIdentity();

        if (!$posts && $curPage > 1) {
            throw new NotFoundHttpException();
        }

        $message = $posts
            ? "Посты по тегу '$tag'"
            : "Постов с тегом '$tag' не найдено. <a class='complaint-link' href='/'>Сбросить результат?</a>";
        Yii::$app
            ->session
            ->setFlash('messageForIndex', $message);

        return $this->render('index', [
            'user' => $user,
            'posts' => $posts,
            'pages' => $pages,
            'curPage' => $curPage,
            'search' => null,
        ]);
    }

    /**
     * Главная страница с постами определенного автора.
     *
     * @throws NotFoundHttpException
     * @throws InvalidConfigException
     */
    public function actionAuthor(string $page = '1'): string
    {
        $path = Yii::$app
            ->request
            ->getPathInfo();

        if ($path === 'author') {
            throw new NotFoundHttpException();
        }

        $author = ltrim(strrchr($path, '/'), '/');
        $posts = Post::find()
            ->byAuthor($author)
            ->all();

        $pages = (int)(ceil(count($posts) / POSTS_ON_PAGE));
        $curPage = (int)$page;
        $posts = Post::find()
            ->byAuthor($author)
            ->orderDescById()
            ->offset(($page - 1) * POSTS_ON_PAGE)
            ->limit(POSTS_ON_PAGE)
            ->all();
        $user = Yii::$app
            ->user
            ->getIdentity();

        if (!$posts && $curPage > 1) {
            throw new NotFoundHttpException();
        }

        $message = $posts
            ? "Посты автора '$author'"
            : "Постов от автора '$author' не найдено. <a class='complaint-link' href='/'>Сбросить результат?</a>";
        Yii::$app
            ->session
            ->setFlash('messageForIndex', $message);

        return $this->render('index', [
            'user' => $user,
            'posts' => $posts,
            'pages' => $pages,
            'curPage' => $curPage,
            'search' => null,
        ]);
    }
}
