<?php

namespace app\controllers;

use app\models\Post;
use app\models\Statistics;
use yii\web\NotFoundHttpException;
use Yii;

class InterfaceController extends AppController
{
    public function actionPost()
    {
        if (!Yii::$app->request->post()) {
            throw new NotFoundHttpException();
        }

        $postId = $_POST['id'];
        $userId = Yii::$app->session['id'];

        switch ($_POST['postInterface']) {
            case 'like':
                $post = Post::find()
                ->byId($postId)
                ->one();
                $ownerStatistics = Statistics::find()
                    ->byLogin($post->getAuthor())
                    ->one();

                if ($post->isUserDislikeIt($userId)) {
                    $post
                        ->decreaseDislikes()
                        ->bateDislikedByUserId($userId)
                        ->save();
                    $ownerStatistics
                        ->decreaseDislikes()
                        ->save();
                }

                $post
                    ->increaseLikes()
                    ->addLikedByUserId($userId)
                    ->save();
                $post
                    ->updateRating();
                $ownerStatistics
                    ->increaseLikes()
                    ->save();
                $ownerStatistics
                ->updateRating();
                break;
            case 'dislike':
                $post = Post::find()
                    ->byId($postId)
                    ->one();
                $ownerStatistics = Statistics::find()
                    ->byLogin($post->getAuthor())
                    ->one();

                if ($post->isUserLikeIt($userId)) {
                    $post
                        ->decreaseLikes()
                        ->bateLikedByUserId($userId)
                        ->save();
                    $ownerStatistics
                        ->decreaseLikes()
                        ->save();
                }

                $post
                    ->increaseDislikes()
                    ->addDislikedByUserId($userId)
                    ->save();
                $post
                    ->updateRating();
                $ownerStatistics
                    ->increaseDislikes()
                    ->save();
                $ownerStatistics
                    ->updateRating();
                break;
        }

        return $this->redirect('/post?id=' . $postId); //TODO: $this->goBack
    }
}