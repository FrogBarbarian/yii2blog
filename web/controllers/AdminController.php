<?php

namespace app\controllers;
use app\models\Admin;
use Yii;
use yii\web\NotFoundHttpException;

class AdminController extends AppController
{
    public function actionUserPost()
    {
        if (!Yii::$app->session->has('admin')) {
            throw new NotFoundHttpException();
        }
        if (!isset($_GET['id'])) {
            return $this->redirect('/profile');
        }

        $model = new Admin();
        $post = $model->getUserTmpPost($_GET['id']);
        $user = $model->getUser();
        return $this->render('user-post', ['model' => $model, 'post' => $post, 'user' => $user]);
    }

    public function actionConfirm()
    {
        if (!isset($_POST['Admin']['id'])) {
            throw new NotFoundHttpException();
        }
        $model = new Admin();
        $model->initPost();
        return $this->redirect('/profile');
        //TODO: Публикуем статью, отправляем email создателю о публикации
    }
}