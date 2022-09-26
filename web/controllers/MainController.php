<?php

namespace app\controllers;

use app\models\PostInteractionsForm;
use app\models\Posts;

class MainController extends AppController
{






    public function actionEditPost()
    {
        $model = new PostInteractionsForm();
        $post = (new Posts())->getPostById($_GET['id']);
        $_POST['isEdit'] = true;
        return $this->render('new-post', ['model' => $model, 'post' => $post]);
    }
}
