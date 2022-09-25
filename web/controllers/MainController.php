<?php

namespace app\controllers;

use app\models\PostInteractionsForm;
use app\models\Post;

class MainController extends AppController
{






    public function actionEditPost()
    {
        $model = new PostInteractionsForm();
        $post = (new Post())->getPostById($_GET['id']);
        $_POST['isEdit'] = true;
        return $this->render('new-post', ['model' => $model, 'post' => $post]);
    }
}
