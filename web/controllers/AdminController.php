<?php

namespace app\controllers;

use Yii;
use yii\web\NotFoundHttpException;

class AdminController extends AppController
{
//    public function actionUserPost()
//    {
//        if (!Yii::$app->session->has('admin')) {
//            throw new NotFoundHttpException();
//        }
//        if (!isset($_GET['id'])) {
//            return $this->redirect('/profile');
//        }
//
//        $model = new Admin();
//        $post = $model->getUserTmpPost($_GET['id']);
//        $user = $model->getUser($post['author']);
//        return $this->render('user-post', ['model' => $model, 'post' => $post, 'user' => $user]);
//    }
//
//    public function actionConfirm()
//    {
//        if (!isset($_POST['Admin']['id'])) {
//            throw new NotFoundHttpException();
//        }
//        $model = new Admin();
//        $model->initPost();
//        return $this->redirect('/profile');
//        //TODO: Публикуем статью, отправляем email создателю о публикации
//    }

    public function actionAdminPanel()
    {
        if (!Yii::$app->session->has('admin')) {
            throw new NotFoundHttpException();
        }

        return $this->render('panel');

//        if (Yii::$app->session->has('admin')) {
//            $params['admin'] = new Admin();
//            $params['tmpPosts'] = $params['admin']->getUsersTmpPosts();
//            $params['users'] = $model->getUsers();
//        }
    }
//    public function actionEditPost()
//{
//    $model = new PostInteractionsForm();
//    $post = (new Post())->getPostById($_GET['id']);
//    $_POST['isEdit'] = true;
//    return $this->render('new-post', ['model' => $model, 'post' => $post]);
//}
}
