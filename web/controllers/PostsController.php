<?php

namespace app\controllers;

use app\models\Comment;
use app\models\CommentForm;
use app\models\ComplaintForm;
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
     * @throws NotFoundHttpException
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

        return $this->render('index', [
            'posts' => $posts,
            'pages' => $pages,
            'curPage' => $curPage,
            'search' => $search,
        ]);
    }

    /**
     * Отображает страницу с выбранным (по ID из $_GET) постом, если поста с таким ID нет, открывает 404.
     * @param string $id
     * @return string Вид "пост".
     * @throws NotFoundHttpException
     */
    public function actionPost(string $id = '0'): string
    {
        $session = Yii::$app->session;
        $visitorIsLogin = $session->has('login');

        if ($id > 0) {
            $post = Post::find()
                ->byId($id)
                ->one();

            if ($post !== null) {
                if ($visitorIsLogin) {
                    $user = User::find()
                        ->byLogin($session['login'])
                        ->one();
                } else {
                    $user = null;
                }

                $commentForm = new CommentForm();
                $complaintForm = new ComplaintForm();
                $ownerStatistics = Statistics::find()
                    ->byLogin($post->getAuthor())
                    ->one();
                $ownerStatistics
                    ->increaseViews()
                    ->save();
                $post
                    ->increasePostViews()
                    ->save();

                $owner = User::find()
                    ->byLogin($post->getAuthor())
                    ->one();
                $comments = Comment::find()
                    ->byPostId($post->getId())
                    ->orderAscById()
                    ->all();

                return $this->render('post', [
                    'post' => $post,
                    'user' => $user,
                    'owner' => $owner,
                    'comments' => $comments,
                    'commentForm' => $commentForm,
                    'complaintForm' => $complaintForm,
                    'visitorIsLogin' => $visitorIsLogin,
                ]);
            }
        }
        throw new NotFoundHttpException();
    }

    /**
     * Страница создания нового поста.
     * Отправляет валидированные данные в таблицу постов (основную либо во временное хранилище).
     * @return Response|string Редирект на главную/на пост (если данные провалидированы и пост отправлен в БД)/на логин, если не залогинен|Вид "новый пост".
     */
    public function actionNewPost(): Response|string
    {
        $session = Yii::$app->session;
        if (!$session->has('login')) {
            return $this->redirect('/login');
        }
        $model = new PostInteractionsForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($session->has('admin')) {
                $post = new Post();
                $post
                    ->setTitle($model->title)
                    ->setBody($model->body)
                    ->setAuthor($session['login'])
                    ->setTags('test;') //TODO: нужна система присвоения тегов
                    ->save();
                $statistics = Statistics::find()
                    ->byLogin($post->getAuthor())
                    ->one();
                $statistics
                    ->increasePosts()
                    ->save();

                return $this->redirect('/post?id=' . $post->getId());
            }
            $postTmp = new PostTmp();
            $postTmp
                ->setTitle($model->title)
                ->setBody($model->body)
                ->setAuthor($session['login'])
                ->setTags('test;') //TODO: нужна система присвоения тегов
                ->save();
            //TODO: Админу приходит на почту сообщение о новом посте пользователя
            //TODO: Flash message Пост создан и отправлен на одобрение

            return $this->goHome();
        }

        return $this->render('new-post', ['model' => $model]);
    }

    /**
     * Страница редактирования уже созданного поста пользователя.
     * Если автор админ - сразу обновляет пост, если обычный пользователь, то отдает на проверку админу.
     * @return Response|string Отправляет на разные страницы в соответствии с условием|Вид "редактирование поста".
     * @throws Exception|NotFoundHttpException
     */
    public function actionEditPost(string $id = '0'): Response|string
    {
        if (!Yii::$app->session->has('login')) { //Пользователь не залогинен
            return $this->redirect('/login');
        }
        if ($id < 1) {
            throw new NotFoundHttpException();
        }
        $user = Yii::$app->session['login'];
        $post = Post::find()->byId($id)->one();
        if ($post === null || $post->getAuthor() !== $user) { //Пост по ID не найден или пользователь не автор поста
            throw new NotFoundHttpException();
        }
        $model = new PostInteractionsForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) { //Проходим проверку формы
            if (Yii::$app->session->has('admin')) {
                $post
                    ->setTitle($model->title)
                    ->setBody($model->body)
                    ->save();
                return $this->redirect('/post?id=' . $post->getId());
            } else {
                if (PostTmp::find()->byUpdatedId($post->getId())->one() !== null) { //Если пост уже редактировался, то переправляем на страницу поста и выводим сообщение пользователю
                    $message = 'Пост уже редактировался и ожидает одобрения админом.';
                    Yii::$app->session->setFlash('postAlreadyUpdated', $message);

                    return $this->redirect('/post?id=' . $post->getId());
                }
                $postTmp = new PostTmp();
                $postTmp
                    ->setTitle($model->title)
                    ->setBody($model->body)
                    ->setAuthor($user)
                    ->setIsNew(false)
                    ->setUpdateId($post->getId())
                    ->save();
                //TODO: Админу приходит на почту сообщение об измененном посте, если пост обновил не админ
                //TODO: Flash message Изменения приняты и отправлены на одобрение

                return $this->goHome();
            }
        }

        return $this->render('new-post', ['model' => $model, 'post' => $post]);
    }

    /**
     * Удаляет пост.
     * @throws NotFoundHttpException
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public function actionDeletePost(): Response
    {
        $request = Yii::$app->getRequest();
        $session = Yii::$app->session;

        if (!$request->isAjax && !isset($_REQUEST['ajax'])) {
            throw new NotFoundHttpException();
        }

        $postId = $request->post('ajax')['postId'];
        $post = Post::find()
            ->byId($postId)
            ->one();
        $ownerStatistics = Statistics::find()
            ->byLogin($post->getAuthor())
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
                ->byLogin($comment->getAuthor())
                ->one();
            $commentOwnerStatistics
                ->decreaseLikes($comment->getLikes())
                ->decreaseDislikes($comment->getDislikes())
                ->decreaseComments()
                ->save();
            $commentOwnerStatistics->updateRating();
            $comment->delete();
        }

        $session->setFlash('messageForIndex', "Пост '<b>{$post->getTitle()}</b>' удален.");
        $post->delete();

        return $this->asJson('/');
    }

    /**
     * Добавляет комментарий к посту.
     * @throws NotFoundHttpException
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
            $postId = $request->post('CommentForm')['postId'];
            $post = Post::find()
                ->byId($postId)
                ->one();
            $user = User::find()
                ->byId(Yii::$app->session['id'])
                ->one();
            $comment = new Comment();
            $comment
                ->setPostId($post->getId())
                ->setAuthor($user->getLogin())
                ->setAuthorId($user->getId())
                ->setComment($commentForm->comment)
                ->save();
            $userStatistics = Statistics::find()
                ->byLogin($user->getLogin())
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
        if ($id === null || $id < 1) {
            throw new NotFoundHttpException();
        }

        $id = (int)$id;
        $comment = Comment::find()
            ->byId($id)
            ->one();

        if ($comment === null) {
            throw new NotFoundHttpException();
        }

        $postId = $comment
            ->getPostId();

        return $this->redirect("/post?id=$postId#comment$id");
    }
}
