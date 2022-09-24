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
        $post = $model->getUserTmpPost();
        $user = $model->getUser();
        return $this->render('user-post', ['post' => $post, 'user' => $user]);
    }
}