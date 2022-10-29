<?php

declare(strict_types=1);

namespace app\controllers;

use app\models\Comment;
use app\models\CommentForm;
use app\models\Post;
use app\models\PostEditorForm;
use app\models\Statistic;
use app\models\Tag;
use app\models\Test;
use app\models\User;
use src\helpers\NormalizeData;
use Yii;
use yii\data\Pagination;
use yii\db\Exception;
use yii\web\NotFoundHttpException;
use yii\web\Response;

class PostsController extends AppController
{
    /**
     * Главная страница постами, здесь же выводятся результаты поиска постов.
     * @return string Вид "главная страница".
     * @throws \Throwable
     */
    public function actionIndex(string $page = '1', string $search = null): string
    {
        if ($search === '') {
            $search = null;
        }

        if (!is_numeric($page) || $page < 1) {
            throw new NotFoundHttpException();
        }

        $curPage = (int)$page;

        if ($search !== null) {
            $posts = Post::find()
                ->postHasWords($search)
                ->orderDescById()
                ->offset(($page - 1) * POSTS_ON_PAGE)
                ->limit(POSTS_ON_PAGE)
                ->all();
            $postAmount = count(Post::find()
                ->postHasWords($search)
                ->asArray()
                ->all());
            $pages = intval(ceil($postAmount / POSTS_ON_PAGE));
            $message = $posts
                ? "Результат поиска по фразе '$search'"
                : "К сожалению, по запросу '$search' ничего не найдено. <a class='complaint-link' href='/'>Сбросить результат?</a>";
            Yii::$app->session->setFlash('messageForIndex', $message);
        } else {
            $posts = Post::find()
                ->orderDescById()
                ->offset(($page - 1) * POSTS_ON_PAGE)
                ->limit(POSTS_ON_PAGE)
                ->all();
            $pages = intval(ceil(Post::find()->count() / POSTS_ON_PAGE));
        }

        if (!$posts && $search === null || $search !== null && $page > 1 && $page > $pages) {
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
     * Отображает страницу с выбранным (по ID из $_GET) постом, если поста с таким ID нет, открывает 404.
     * @param string $id
     * @return string Вид "пост".
     * @throws \Throwable
     */
    public function actionPost(string $id = '0'): string
    {
        $visitorIsLogin = !Yii::$app
            ->user
            ->isGuest;
        $id = (int)$id;

        if ($id > 0) {
            $post = Post::find()
                ->byId($id)
                ->one();

            if ($post !== null) {
                $commentForm = new CommentForm();
                $ownerStatistics = Statistic::find()
                    ->byUsername($post->getAuthor())
                    ->one();
                $ownerStatistics
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
     * Страница создания нового поста.
     * @throws NotFoundHttpException
     */
    public function actionNewPost(): string
    {
        $user = Yii::$app->user->getIdentity();

        if ($user === null) {
            throw new NotFoundHttpException();
        }

        $postEditorForm = new PostEditorForm();

        return $this->render('post-editor', ['postEditorForm' => $postEditorForm]);
    }

    /**
     * Страница редактирования уже созданного поста пользователя.
     * @throws Exception|NotFoundHttpException
     */
    public function actionEditPost(string $id = '0'): string
    {
        $user = Yii::$app->user->getIdentity();

        $id = (int)$id;

        if ($id < 1 || !$user === null) {
            throw new NotFoundHttpException();
        }

        $post = Post::find()
            ->byId($id)
            ->one();

        if ($post === null || $post->getAuthor() !== $user->getUsername()) {
            throw new NotFoundHttpException();
        }

        $postEditorForm = new PostEditorForm();


        return $this->render('post-editor', ['postEditorForm' => $postEditorForm, 'post' => $post]);
    }

    /**
     * Удаляет пост.
     * @throws NotFoundHttpException
     * @throws \Throwable
     */
    public function actionDeletePost(): Response
    {
        $request = Yii::$app->getRequest();

        if (!$request->isAjax && !isset($_REQUEST['ajax'])) {
            throw new NotFoundHttpException();
        }

        $postId = (int)$request->post('ajax')['postId'];
        $post = Post::find()
            ->byId($postId)
            ->one();
        $ownerStatistics = Statistic::find()
            ->byUsername($post->getAuthor())
            ->one();
        $ownerStatistics
            ->decreasePosts()
            ->decreaseViews($post->getViews())
            ->decreaseLikes($post->getLikes())
            ->decreaseDislikes($post->getDislikes())
            ->save();
        $ownerStatistics->updateRating();
        $comments = Comment::find()
            ->byPostId($postId)
            ->all();

        foreach ($post->getTagsArray() as $tag) {
            $tagObj = Tag::find()
                ->byTag($tag)
                ->one();
            $tagObj
                ->decreaseAmountOfUse()
                ->save();
        }

        foreach ($comments as $comment) {
            $commentOwnerStatistics = Statistic::find()
                ->byUsername($comment->getAuthor())
                ->one();
            $commentOwnerStatistics
                ->decreaseLikes($comment->getLikes())
                ->decreaseDislikes($comment->getDislikes())
                ->decreaseComments()
                ->save();
            $commentOwnerStatistics->updateRating();
            $comment->delete();
        }

        Yii::$app
            ->session
            ->setFlash('messageForIndex', "Пост '<b>{$post->getTitle()}</b>' удален.");
        $post->delete();

        return $this->asJson('/');
    }


    /**
     * Формирует ссылку на комментарий.
     * @throws NotFoundHttpException
     */
    public function actionComment(string $id = null): Response
    {
        if ($id === null) {
            throw new NotFoundHttpException();
        }

        $id = (int)$id;
        $comment = Comment::find()
            ->byId($id)
            ->one();

        if ($comment === null) {
            throw new NotFoundHttpException();
        }

        $postId = $comment->getPostId();

        return $this->redirect("/post?id=$postId#comment$id");
    }

    /**
     * Выгружает статьи по определенному тегу.
     * @throws \Throwable
     */
    public function actionTag(string $page = '1'): string
    {
        $path = Yii::$app->request->getPathInfo();

        if ($path === 'tag') {
            throw new NotFoundHttpException();
        }

        $tag = ltrim(strrchr($path, '/'), '/');
        $posts = Post::find()
            ->byTag($tag)
            ->all();
        $message = $posts === []
            ? "Постов с тегом '$tag' не найдено. <a class='complaint-link' href='/'>Сбросить результат?</a>"
            : "Посты по тегу '$tag'";
        Yii::$app->session->setFlash('messageForIndex', $message);
        Yii::$app->session->setFlash('messageForIndex', $message);

        $pages = intval(ceil(count($posts) / POSTS_ON_PAGE));
        $curPage = (int)$page;
        $posts = Post::find()
            ->byTag($tag)
            ->orderDescById()
            ->offset(($page - 1) * POSTS_ON_PAGE)
            ->limit(POSTS_ON_PAGE)->all();
        $user = Yii::$app
            ->user
            ->getIdentity();

        return $this->render('index', [
            'user' => $user,
            'posts' => $posts,
            'pages' => $pages,
            'curPage' => $curPage,
            'search' => null,
        ]);
    }

    /**
     * Выгружает статьи определенного автора.
     * @throws \Throwable
     */
    public function actionAuthor(string $page = '1'): string
    {
        $path = Yii::$app->request->getPathInfo();

        if ($path === 'author') {
            throw new NotFoundHttpException();
        }

        $author = ltrim(strrchr($path, '/'), '/');
        $posts = Post::find()
            ->byAuthor($author)
            ->all();
        $message = $posts === []
            ? "Постов от автора '$author' не найдено. <a class='complaint-link' href='/'>Сбросить результат?</a>"
            : "Посты автора '$author'";
        Yii::$app->session->setFlash('messageForIndex', $message);

        $pages = intval(ceil(count($posts) / POSTS_ON_PAGE));
        $curPage = (int)$page;
        $posts = Post::find()
            ->byAuthor($author)
            ->orderDescById()
            ->offset(($page - 1) * POSTS_ON_PAGE)
            ->limit(POSTS_ON_PAGE)->all();
        $user = Yii::$app
            ->user
            ->getIdentity();

        return $this->render('index', [
            'user' => $user,
            'posts' => $posts,
            'pages' => $pages,
            'curPage' => $curPage,
            'search' => null,
        ]);
    }

    /**
     * Запрещает/разрешает комментирование поста.
     * @throws NotFoundHttpException
     */
    public function actionCommentRule(): Response
    {
        $request = Yii::$app->getRequest();

        if (!$request->getIsAjax() && !isset($_REQUEST['ajax'])) {
            throw new NotFoundHttpException();
        }

        $postId = (int)$request->post('ajax')['postId'];
        $post = Post::find()
            ->byId($postId)
            ->one();
        $post
            ->setIsCommentable(!$post->getisCommentable())
            ->save();
        $isCommentable = $post->getIsCommentable();

        return $this->asJson($isCommentable);
    }

    /**
     * Обновляет отображаемое количество комментариев.
     * @throws NotFoundHttpException
     */
    public function actionUpdateCommentsAmount(): Response
    {
        $request = Yii::$app->getRequest();

        if (!$request->getIsAjax() && !isset($_REQUEST['ajax'])) {
            throw new NotFoundHttpException();
        }

        $postId = (int)$request->post('ajax')['postId'];
        $curCommentsAmount = (int)$request->post('ajax')['curCommentsAmount'];
        $post = Post::find()
            ->byId($postId)
            ->one();
        $postCommentsAmount = $post->getCommentsAmount();

        if ($postCommentsAmount == $curCommentsAmount) {
            return $this->asJson(false);
        }

        $wordForm = NormalizeData::wordForm(
            $postCommentsAmount, 'комментариев',
            'комментарий',
            'комментария',
        );

        return $this->asJson("$postCommentsAmount $wordForm");
    }
}
