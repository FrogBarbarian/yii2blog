<?php

namespace app\controllers;

use app\models\PostInteractionsForm;
use app\models\Post;
use app\models\Posts;
use yii\db\Exception;
use Yii;
use yii\web\Response;

class PostsController extends AppController
{
    /**
     * @return string Главная страница.
     * @throws Exception
     */
    public function actionIndex(): string
    {
        $model = new Posts();
        $posts = $model->getPosts();
        return $this->render('index', ['posts' => $posts, 'model' => $model]);
    }

    /**
     * Отображает страницу с выбранным (по ID из $_GET) постом, если поста с таким ID нет, то перенаправляет на главную.
     * @return string|void
     * @throws Exception
     */
    public function actionPost()
    {
        if (isset($_GET['id']) && $_GET['id'] > 0) {
            $model = new Post();
            $model->increasePostViews($_GET['id']);
            $post = $model->getPostById($_GET['id']);
            $user = Yii::$app->session['login'] ?? '_guest';
            if ($post) {
                return $this->render('post',['model' => $model, 'post' => $post, 'user' => $user]);
            }
        }
        $this->goHome();
    }

    /**
     * Находит рандомный пост и открывает страничку с ним.
     * @return void
     * @throws Exception
     */
    public function actionRandom(): void
    {
        $model = new Post();
        $post = $model->getRandomPost();
        $this->redirect('/post?id=' . $post['id']);
    }

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
}
