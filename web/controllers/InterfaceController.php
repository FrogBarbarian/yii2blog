<?php

namespace app\controllers;

use app\models\Post;
use app\models\Statistics;
use src\helpers\ConstructHtml;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use Yii;

class InterfaceController extends AppController
{
    /**
     * Находит случайный пост.
     * @throws NotFoundHttpException
     */
    public function actionRandomPost(): Response
    {
        if (Yii::$app->getRequest()->getIsAjax()) {
            $post = Post::find()
                ->random()
                ->one();

            return $this->asJson('/post?id=' . $post->getId());
        }

        throw new NotFoundHttpException();
    }

    /**
     * Добавляет лайк посту.
     * @throws NotFoundHttpException
     */
    public function actionLikePost(): Response
    {

        if (Yii::$app->getRequest()->getIsAjax() && isset($_REQUEST['ajax'])) {
            $userId = $_REQUEST['ajax']['userId'];
            $postId = $_REQUEST['ajax']['postId'];
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
            } else {
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
                $ownerStatistics
                    ->increaseLikes()
                    ->save();
            }

            $post
                ->updateRating();
            $ownerStatistics
                ->updateRating();

            return $this->asJson(ConstructHtml::rating($post->getRating()));
        }

        throw new NotFoundHttpException();
    }

    /**
     * Добавляет дизлайк посту.
     * @throws NotFoundHttpException
     */
    public function actionDislikePost(): Response
    {
        $request = Yii::$app->getRequest();

        if (!$request->getIsAjax() && !isset($_REQUEST['ajax'])) {
            throw new NotFoundHttpException();
        }

        $userId = $request->get('ajax')['userId'];
        $postId = $request->get('ajax')['postId'];
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
        } else {
            $post
                ->increaseDislikes()
                ->addDislikedByUserId($userId)
                ->save();
            $ownerStatistics
                ->increaseDislikes()
                ->save();
        }

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
            ->updateRating();
        $ownerStatistics
            ->updateRating();

        return $this->asJson(ConstructHtml::rating($post->getRating()));
    }

    /**
     * Обновляет рейтинг на странице поста.
     * @throws NotFoundHttpException
     */
    public function actionUpdatePostRating(): Response
    {
        if (Yii::$app->getRequest()->getIsAjax() && isset($_REQUEST['ajax'])) {
            $request = Yii::$app->getRequest()->get();
            $postId = $request['ajax']['postId'];
            $post = Post::find()
                ->byId($postId)
                ->one();

            return $this->asJson(ConstructHtml::rating($post->getRating()));
        }

        throw new NotFoundHttpException();
    }
}
