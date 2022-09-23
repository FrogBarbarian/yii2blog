<?php

namespace app\controllers;

use app\models\Posts;
use app\models\RegisterForm;
use Yii;

class MainController extends AppController
{
    public function actionIndex(): string
    {
        $model = new Posts();
        $posts = $model->getPosts();
        return $this->render('index', ['posts' => $posts, 'model' => $model]);
    }

    public function actionPost()
    {
        if (isset($_GET['id'])) {
            $model = new Posts();
            $model->increasePostViews($_GET['id']);
            $post = $model->getPostById($_GET['id']);
            if ($post) {
                return $this->render('post',['post' => $post, 'model' => $model]);
            }
        }
        $this->goHome();
    }

    public function actionRandom(): void
    {
        $model = new Posts();
        $post = $model->getRandomPost();
        $this->redirect('/post?id=' . $post['id']);
    }

    public function actionAbout()
    {
        return $this->render('about');
    }

    public function actionRegister()
    {
        $model = new RegisterForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {

        }

        return $this->render('register', ['model' => $model]);
    }
}