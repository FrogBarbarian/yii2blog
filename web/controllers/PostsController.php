<?php

declare(strict_types = 1);

namespace app\controllers;

use app\models\Comment;
use app\models\CommentForm;
use app\models\PostInteractionsForm;
use app\models\Post;
use app\models\PostTmp;
use app\models\Statistics;
use app\models\User;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use yii\db\Exception;
use Yii;

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
                $ownerStatistics = Statistics::find()
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
     * TODO: переделать
     * Страница создания нового поста.
     * Отправляет валидированные данные в таблицу постов (основную либо во временное хранилище).
     * @return Response|string Редирект на главную/на пост (если данные провалидированы и пост отправлен в БД)/на логин, если не залогинен|Вид "новый пост".
     */
    public function actionNewPost(): Response|string
    {
        $user = Yii::$app->user->getIdentity();

        if ($user === null) {
            return $this->redirect('/login');
        }

        $postInteractionsForm = new PostInteractionsForm();

        if ($postInteractionsForm->load(Yii::$app->request->post()) && $postInteractionsForm->validate()) {
            if ($user->getIsAdmin()) {
                $post = new Post();
                $post
                    ->setTitle($postInteractionsForm->title)
                    ->setBody($postInteractionsForm->body)
                    ->setAuthor($user->getusername())
                    ->setTags($postInteractionsForm->tags)
                    ->save();
                $statistics = Statistics::find()
                    ->byUsername($post->getAuthor())
                    ->one();
                $statistics
                    ->increasePosts()
                    ->save();

                return $this->redirect('/post?id=' . $post->getId());
            }
            $postTmp = new PostTmp();
            $postTmp
                ->setTitle($postInteractionsForm->title)
                ->setBody($postInteractionsForm->body)
                ->setTags($postInteractionsForm->tags)
                ->setAuthor($user->getUsername())
                ->setTags('test;') //TODO: нужна система присвоения тегов
                ->save();
            //TODO: Админу приходит на почту сообщение о новом посте пользователя
            //TODO: Flash message Пост создан и отправлен на одобрение

            return $this->goHome();
        }

        return $this->render('new-post', ['postInteractionsForm' => $postInteractionsForm]);
    }

    /**
     * TODO: переделать
     * Страница редактирования уже созданного поста пользователя.
     * Если автор админ - сразу обновляет пост, если обычный пользователь, то отдает на проверку админу.
     * @return Response|string Отправляет на разные страницы в соответствии с условием|Вид "редактирование поста".
     * @throws Exception|NotFoundHttpException
     */
    public function actionEditPost(string $id = '0'): Response|string
    {
        $user = Yii::$app->user->getIdentity();

        if (!$user === null) {
            return $this->redirect('/login');
        }

        $id = (int)$id;

        if ($id < 1) {
            throw new NotFoundHttpException();
        }

        $post = Post::find()
            ->byId($id)
            ->one();

        if ($post === null || $post->getAuthor() !== $user->getUsername()) {
            throw new NotFoundHttpException();
        }

        $postInteractionsForm = new PostInteractionsForm();

        if ($postInteractionsForm->load(Yii::$app->request->post()) && $postInteractionsForm->validate()) {
            if ($user->getIsAdmin()) {
                $post
                    ->setTitle($postInteractionsForm->title)
                    ->setBody($postInteractionsForm->body)
                    ->setTags($postInteractionsForm->tags)
                    ->save();

                return $this->redirect('/post?id=' . $post->getId());
            } else {
                if (PostTmp::find()->byUpdatedId($post->getId())->one() !== null) {
                    $message = 'Пост уже редактировался и ожидает одобрения админом.';
                    Yii::$app->session->setFlash('postAlreadyUpdated', $message);

                    return $this->redirect('/post?id=' . $post->getId());
                }

                $postTmp = new PostTmp();
                $postTmp
                    ->setTitle($postInteractionsForm->title)
                    ->setBody($postInteractionsForm->body)
                    ->setTags($postInteractionsForm->tags)
                    ->setAuthor($user->getUsername())
                    ->setIsNew(false)
                    ->setUpdateId($post->getId())
                    ->save();
                //TODO: Админу приходит на почту сообщение об измененном посте, если пост обновил не админ
                //TODO: Flash message Изменения приняты и отправлены на одобрение

                return $this->goHome();
            }
        }

        return $this->render('new-post', ['postInteractionsForm' => $postInteractionsForm, 'post' => $post]);
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
        $ownerStatistics = Statistics::find()
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

        foreach ($comments as $comment) {
            $commentOwnerStatistics = Statistics::find()
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
     * Добавляет комментарий к посту.
     * @throws \Throwable
     */
    public
    function actionAddComment(): Response
    {
        $request = Yii::$app->getRequest();

        if (!$request->getIsAjax()) {
            throw new NotFoundHttpException();
        }

        $commentForm = new CommentForm();

        if ($commentForm->load($request->post()) && $commentForm->validate()) {
            $postId = (int)$request->post('CommentForm')['postId'];
            $post = Post::find()
                ->byId($postId)
                ->one();
            $user = Yii::$app
                ->user
                ->getIdentity();
            $comment = new Comment();
            $comment
                ->setPostId($post->getId())
                ->setAuthor($user->getUsername())
                ->setAuthorId($user->getId())
                ->setComment($commentForm->comment)
                ->save();
            $userStatistics = Statistics::find()
                ->byUsername($user->getUsername())
                ->one();
            $userStatistics
                ->increaseComments()
                ->save();
            $post
                ->increaseCommentsAmount()
                ->save();

            return $this->asJson(false);
        }

        return $this->asJson($commentForm->errors);
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
}
