<?php

declare(strict_types=1);

namespace app\controllers;

use app\models\Post;
use app\models\Statistic;
use app\models\Tag;
use app\models\UploadForm;
use src\helpers\ConstructHtml;
use Yii;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use yii\web\UploadedFile;
use yii\widgets\ActiveForm;

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

        $userId = Yii::$app
            ->user
            ->getId();
        $postId = (int)$request->post('ajax')['postId'];
        $post = Post::find()
            ->byId($postId)
            ->one();
        $ownerStatistics = Statistic::find()
            ->byUsername($post->getAuthor())
            ->one();

        if ($post->isUserAlreadyLikedPost($userId)) {
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

        if ($post->isUserAlreadyDislikedPost($userId)) {
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

        $userId = Yii::$app
            ->user
            ->getId();
        $postId = (int)$request->post('ajax')['postId'];
        $post = Post::find()
            ->byId($postId)
            ->one();
        $ownerStatistics = Statistic::find()
            ->byUsername($post->getAuthor())
            ->one();

        if ($post->isUserAlreadyDislikedPost($userId)) {
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

        if ($post->isUserAlreadyLikedPost($userId)) {
            $post
                ->decreaseLikes()
                ->bateLikedByUserId($userId)
                ->save();
            $ownerStatistics
                ->decreaseLikes()
                ->save();
        }

        $post->updateRating();
        $ownerStatistics->updateRating();
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

        $userId = Yii::$app
            ->user
            ->getId();
        $postId = (int)$request->post('ajax')['postId'];
        $post = Post::find()
            ->byId($postId)
            ->one();
        $liked = $post->isUserAlreadyLikedPost($userId);
        $disliked = $post->isUserAlreadyDislikedPost($userId);

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
