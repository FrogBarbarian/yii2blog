<?php

declare(strict_types=1);

namespace app\controllers;

use src\helpers\ConstructHtml;
use app\models\Post;
use app\models\Statistics;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use Yii;

/**
 * Отвечает за UI/UX при работе с постом.
 */
class PostUIController extends AppController
{
    /**
     * Добавляет лайк посту.
     * @throws NotFoundHttpException
     */
    public function actionLikePost()
    {
        $request = Yii::$app->getRequest();

        if (!Yii::$app->getRequest()->getIsAjax() && isset($_REQUEST['ajax'])) {
            throw new NotFoundHttpException();

        }

        $userId = (int)Yii::$app->session['id'];
        $postId = (int)$request->post('ajax')['postId'];
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
            $post
                ->increaseLikes()
                ->addLikedByUserId($userId)
                ->save();
            $ownerStatistics
                ->increaseLikes()
                ->save();
        }

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
            ->updateRating();
        $ownerStatistics
            ->updateRating();
    }

    /**
     * Добавляет дизлайк посту.
     * @throws NotFoundHttpException
     */
    public function actionDislikePost()
    {
        $request = Yii::$app->getRequest();

        if (!$request->getIsAjax() && !isset($_REQUEST['ajax'])) {
            throw new NotFoundHttpException();
        }

        $userId = (int)Yii::$app->session['id'];
        $postId = (int)$request->post('ajax')['postId'];
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
    }

    /**
     * Обновляет цвет на кнопках лайк/дизлайк поста.
     * @throws NotFoundHttpException
     */
    public function actionUpdatePostRatingButtons(): Response
    {
        $request = Yii::$app->getRequest();

        if (!$request->getIsAjax() && !isset($_REQUEST['ajax'])) {
            throw new NotFoundHttpException();
        }

        $userId = (int)Yii::$app->session['id'];
        $postId = (int)$request->post('ajax')['postId'];
        $post = Post::find()
            ->byId($postId)
            ->one();
        $liked = $post
            ->isUserLikeIt($userId);
        $disliked = $post
            ->isUserDislikeIt($userId);

        return $this->asJson([$liked, $disliked]);
    }

    /**
     * Обновляет рейтинг на странице поста.
     * @throws NotFoundHttpException
     */
    public function actionUpdatePostRating(): Response
    {
        $request = Yii::$app->getRequest();

        if (!$request->getIsAjax() && !isset($_REQUEST['ajax'])) {
            throw new NotFoundHttpException();
        }

        $postId = (int)$request->post('ajax')['postId'];
        $curRating = (int)$request->post('ajax')['curRating'];
        $post = Post::find()
            ->byId($postId)
            ->one();

        if ($post->getRating() == $curRating) {
            return $this->asJson(false);
        }

        return $this->asJson(ConstructHtml::rating($post->getRating()));
    }
}
