<?php

namespace app\controllers;

use app\models\PostInteractionsForm;
use app\models\Posts;
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
        $posts = Posts::find()->descById()->all();
        return $this->render('index', ['posts' => $posts]);
    }

    //TODO: Продолжаем переделывать на Active Query
    /**
     * Отображает страницу с выбранным (по ID из $_GET) постом, если поста с таким ID нет, открывает 404.
     * @param string $id
     * @return string Вид "пост".
     * @throws NotFoundHttpException
     */
    public function actionPost(string $id = '0'): string
    {
        if ($id > 0) {
            $post = Posts::find()->byId($id)->one();
            if ($post !== null) {
                ++$post->viewed;
                $post->save();
                $model = new Posts();
                $user = Yii::$app->session['login'] ?? '_guest';
                return $this->render('post', ['model' => $model, 'post' => $post, 'user' => $user]);
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
        $model = new Posts();
        $post = $model->getRandomPost();
        return $this->redirect('/post?id=' . $post['id']);
    }

    /**
     * Страница создания нового поста, отправляет валидированные данные в таблицу постов (основную, либо во временное хранилище).
     * @return Response|string Редирект на главную (если данные провалидированы и пост отправлен в БД)|Страница создания поста.
     * @throws Exception
     */
    public function actionNewPost(): Response|string
    {
        if (!Yii::$app->session->has('login')) {
            return $this->redirect('/login');
        }

        $model = new PostInteractionsForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $model->createPost();
            //TODO: Админу приходит на почту сообщение о новом посте, если пост создал не админ
            return $this->goHome();
        }
        return $this->render('new-post', ['model' => $model]);
    }

    /**
     * Страница редактирования уже созданного поста пользователя.
     * Если автор админ - сразу обновляет пост, если обычный пользователь, то отдает на проверку админу.
     * @return Response|string Отправляет на разные страницы в соответствии с условием|Страница изменения поста.
     * @throws Exception
     */
    public function actionEditPost(string $id): Response|string
    {
        if (!Yii::$app->session->has('login')) { //Пользователь не залогинен
            return $this->redirect('/login');
        }
        if (!isset($_GET['id']) || $_GET['id'] < 1) { //Нет GET параметра с ID поста или его значение не валидно
            return $this->goHome();
        }
        var_dump(Posts::find()->byId($id)->one()['title']); die();
        $post = Posts::find()->byId($id)->one();
        $post = (new Posts())->getPostById($_GET['id']);
        if (!$post || $post['author'] !== Yii::$app->session['login']) { //Пост по ID не найден или пользователь не автор поста
            return $this->goHome();
        }
        $model = new PostInteractionsForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) { //Проходим проверку формы
            if (Yii::$app->session->has('admin')) {
                $model->updatePost($post['id']);
                return $this->redirect('/post?id=' . $post['id']);
            } else {
                if ($model->checkIsUpdate($post['id'])) { //Если пост уже редактировался, то переправляем на страницу поста и выводим сообщение пользователю
                    $message = 'Пост уже редактировался и ожидает одобрения админом.';
                    Yii::$app->session->setFlash('postAlreadyUpdated', $message);
                    return $this->redirect('/post?id=' . $post['id']);
                }
                $model->createPost($post['id']);
                //TODO: Админу приходит на почту сообщение об измененном посте, если пост обновил не админ
                return $this->goHome();
            }
        }
        return $this->render('new-post', ['model' => $model, 'post' => $post]);
    }
}
