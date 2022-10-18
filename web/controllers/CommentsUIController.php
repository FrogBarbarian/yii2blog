<?php

declare(strict_types=1);

namespace app\controllers;

use app\models\Comment;
use app\models\Statistic;
use src\helpers\ConstructHtml;
use Yii;
use app\models\Post;
use src\helpers\NormalizeData;
use yii\web\NotFoundHttpException;
use yii\web\Response;

/**
 * Отвечает за UI/UX при работе с комментариями.
 */
class CommentsUIController extends AppController
{
    /**
     * Обновляет отображаемое количество комментариев.
     * @throws NotFoundHttpException
     */
    public function actionUpdateCommentsAmount(): Response
    {
        $request = Yii::$app->getRequest();

        if (!$request->getIsAjax() && !isset($_REQUEST['ajax'])) {
            throw new NotFoundHttpException();
        }

        $postId = (int)$request->post('ajax')['postId'];
        $curCommentsAmount = (int)$request->post('ajax')['curCommentsAmount'];
        $post = Post::find()
            ->byId($postId)
            ->one();
        $postCommentsAmount = $post->getCommentsAmount();

        if ($postCommentsAmount == $curCommentsAmount) {
            return $this->asJson(false);
        }

        $wordForm = NormalizeData::wordForm(
            $postCommentsAmount, 'комментариев',
            'комментарий',
            'комментария',
        );

        return $this->asJson("$postCommentsAmount $wordForm");
    }

    /**
     * Конструирует комментарии, еще не отрисованные на странице.
     * @throws NotFoundHttpException
     */
    public function actionAppendComments(): Response
    {
        $request = Yii::$app->getRequest();

        if (!$request->getIsAjax() && !isset($_REQUEST['ajax'])) {
            throw new NotFoundHttpException();
        }

        $postId = (int)$request->post('ajax')['postId'];
        $curCommentsAmount = (int)$request->post('ajax')['curCommentsAmount'];
        $post = Post::find()
            ->byId($postId)
            ->one();
        $postCommentsAmount = $post->getCommentsAmount();

        if ($postCommentsAmount == $curCommentsAmount) {
            return $this->asJson(false);
        }

        $diff = $postCommentsAmount - $curCommentsAmount;
        $comments = Comment::find()
            ->byPostId($postId)
            ->orderDescById()
            ->limit($diff)
            ->all();

        return $this->asJson(ConstructHtml::comments($comments));
    }


    /**
     * Запрещает/разрешает комментирование поста.
     * @throws NotFoundHttpException
     */
    public function actionCommentRule(): Response
    {
        $request = Yii::$app->getRequest();

        if (!$request->getIsAjax() && !isset($_REQUEST['ajax'])) {
            throw new NotFoundHttpException();
        }

        $postId = (int)$request->post('ajax')['postId'];
        $post = Post::find()
            ->byId($postId)
            ->one();
        $post
            ->setIsCommentable(!$post->getisCommentable())
            ->save();
        $isCommentable = $post->getIsCommentable();

        return $this->asJson($isCommentable);
    }

    /**
     * Добавляет комментарию лайк.
     * @throws NotFoundHttpException
     */
    public function actionLikeComment(): Response
    {
        $request = Yii::$app->getRequest();

        if (!$request->getIsAjax() && !isset($_REQUEST['ajax'])) {
            throw new NotFoundHttpException();
        }

        $commentId = (int)$request->post('ajax')['commentId'];
        $userId = Yii::$app
            ->user
            ->getId();
        $comment = Comment::find()
            ->byId($commentId)
            ->one();
        $ownerStatistics = Statistic::find()
            ->byUsername($comment->getAuthor())
            ->one();

        if ($comment->isUserAlreadyDislikedComment($userId)) {
            $comment
                ->decreaseDislikes()
                ->removeDislikedByUserId($userId)
                ->save();
            $ownerStatistics
                ->decreaseDislikes()
                ->save();
        }

        if ($comment->isUserAlreadyLikedComment($userId)) {
            $comment
                ->decreaseLikes()
                ->removeLikedByUserId($userId)
                ->save();
            $ownerStatistics
                ->decreaseLikes()
                ->save();
        } else {
            $comment
                ->increaseLikes()
                ->addLikedByUserId($userId)
                ->save();
            $ownerStatistics
                ->increaseLikes()
                ->save();
        }

        $ownerStatistics
            ->updateRating();
        $comment
            ->updateRating();

        return $this->asJson(ConstructHtml::rating($comment->getRating()));
    }

    /**
     * Добавляет комментарию лайк.
     * @throws NotFoundHttpException
     */
    public function actionDislikeComment(): Response
    {
        $request = Yii::$app->getRequest();

        if (!$request->getIsAjax() && !isset($_REQUEST['ajax'])) {
            throw new NotFoundHttpException();
        }

        $commentId = (int)$request->post('ajax')['commentId'];
        $userId = Yii::$app
            ->user
            ->getId();
        $comment = Comment::find()
            ->byId($commentId)
            ->one();
        $ownerStatistics = Statistic::find()
            ->byUsername($comment->getAuthor())
            ->one();

        if ($comment->isUserAlreadyLikedComment($userId)) {
            $comment
                ->decreaseLikes()
                ->removeLikedByUserId($userId)
                ->save();
            $ownerStatistics
                ->decreaseLikes()
                ->save();
        }

        if ($comment->isUserAlreadyDislikedComment($userId)) {
            $comment
                ->decreaseDislikes()
                ->removeDislikedByUserId($userId)
                ->save();
            $ownerStatistics
                ->decreaseDislikes()
                ->save();
        } else {
            $comment
                ->increaseDislikes()
                ->addDislikedByUserId($userId)
                ->save();
            $ownerStatistics
                ->increaseDislikes()
                ->save();
        }

        $ownerStatistics
            ->updateRating();
        $comment
            ->updateRating();

        return $this->asJson(ConstructHtml::rating($comment->getRating()));
    }

    /**
     * Обновляет цвет на кнопках лайк/дизлайк комментария.
     * @throws NotFoundHttpException
     */
    public function actionUpdateCommentRatingButtons(): Response
    {
        $request = Yii::$app->getRequest();

        if (!$request->getIsAjax() && !isset($_REQUEST['ajax'])) {
            throw new NotFoundHttpException();
        }

        $userId = Yii::$app
            ->user
            ->getId();
        $commentId = (int)$request->post('ajax')['commentId'];
        $comment = Comment::find()
            ->byId($commentId)
            ->one();
        $liked = $comment
            ->isUserAlreadyLikedComment($userId);
        $disliked = $comment
            ->isUserAlreadyDislikedComment($userId);

        return $this->asJson([$liked, $disliked]);
    }

    /**
     * Сверяет текущий рейтинг комментариев с рейтингом в БД.
     * @throws NotFoundHttpException
     */
    public function actionCommentsUpdateRating(): Response
    {
        $request = Yii::$app->getRequest();

        if (!$request->getIsAjax() && !isset($_REQUEST['ajax'])) {
            throw new NotFoundHttpException();
        }

        $commentsData = $request->post('ajax')['comments'] ?? null;

        if ($commentsData === null) {
            return $this->asJson(false);
        }

        $newCommentData = [];
        $dataIsDiff = false;

        foreach ($commentsData as $commentData) {
            $id = (int)$commentData['id'];
            $rating = (int)$commentData['rating'];
            $comment = Comment::find()
                ->byId($id)
                ->one();
            $newCommentData[] = ['html' => ConstructHtml::rating($comment->getRating())];

            if ($comment->getRating() !== $rating) {
                $dataIsDiff = true;
            }
        }

        if ($dataIsDiff) {
            return $this->asJson($newCommentData);
        }

        return $this->asJson(false);
    }
}
