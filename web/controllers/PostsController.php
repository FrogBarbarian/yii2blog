<?php

namespace app\controllers;

use app\models\PostInteractionsForm;
use app\models\Post;
use app\models\PostTmp;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use yii\db\Exception;
use Yii;

class PostsController extends AppController
{
    /**
     * @return string Вид "главная страница".
     */
    public function actionIndex(): string
    {
        $posts = Post::find()
            ->orderDescById()
            ->all();

        return $this->render('index', ['posts' => $posts]);
    }

    /**
     * Отображает страницу с выбранным (по ID из $_GET) постом, если поста с таким ID нет, открывает 404.
     * @param string $id
     * @return string Вид "пост".
     * @throws NotFoundHttpException
     */
    public function actionPost(string $id = '0'): string
    {
        if ($id > 0) {
            $post = Post::find()
                ->byId($id)
                ->one();
            if ($post !== null) {
                $post
                    ->setViews($post->getViews() + 1)
                    ->save();
                $user = Yii::$app->session['login'] ?? '_guest';

                return $this->render('post', ['post' => $post, 'user' => $user]);
            }
        }
        throw new NotFoundHttpException();
    }

    /**
     * Находит рандомный пост и открывает страничку с ним.
     * @return Response Редирект на страницу с постом.
     * @throws Exception
     */
    public function actionRandom(): Response
    {
        $post = Post::find()
            ->random()
            ->one();

        return $this->redirect('/post?id=' . $post->getId());
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
                    ->save();

                return $this->redirect('/post?id=' . $post->getId());
            }
            $postTmp = new PostTmp();
            $postTmp
                ->setTitle($model->title)
                ->setBody($model->body)
                ->setAuthor($session['login'])
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
        if ($id < 1) { //Нет GET параметра с ID поста или его значение не валидно
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
}
