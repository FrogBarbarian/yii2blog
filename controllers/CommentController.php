<?php

declare(strict_types=1);

namespace app\controllers;

use app\components\CommentWidget;
use app\models\Comment;
use app\models\CommentForm;
use app\models\Post;
use app\models\Statistic;
use src\helpers\ConstructHtml;
use Yii;
use yii\web\NotFoundHttpException;
use yii\web\Response;

class CommentController extends AppController
{
    /**
     * Добавляет комментарий к посту.
     * @throws \Throwable
     */
    public
    function actionAddComment(): Response
    {
        $request = Yii::$app->getRequest();

        if (!$request->getIsAjax()) {
            throw new NotFoundHttpException();
        }

        $commentForm = new CommentForm();

        if ($commentForm->load($request->post()) && $commentForm->validate()) {
            $postId = (int)$request->post('CommentForm')['postId'];
            $post = Post::find()
                ->byId($postId)
                ->one();
            $user = Yii::$app
                ->user
                ->getIdentity();

            if (!$user->getCanComment() || !$post->getIsCommentable()) {
                $commentForm->addError(
                    'comment',
                    'Что-то пошло не так, попробуйте обновить страницу'
                );
                return $this->asJson($commentForm->errors);
            }

            $comment = new Comment();
            $comment
                ->setPostId($post->getId())
                ->setAuthor($user->getUsername())
                ->setAuthorId($user->getId())
                ->setComment($commentForm->comment)
                ->save();
            $userStatistics = Statistic::find()
                ->byUsername($user->getUsername())
                ->one();
            $userStatistics
                ->increaseComments()
                ->save();
            $post
                ->increaseCommentsAmount()
                ->save();

            return $this->asJson(false);
        }

        return $this->asJson($commentForm->errors);
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
     * Добавляет комментарию дизлайк.
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
        $user = Yii::$app->user->getIdentity();
        $comments = Comment::find()
            ->byPostId($postId)
            ->orderDescById()
            ->limit($diff)
            ->all();
        $html = '';

        foreach ($comments as $comment) {
            $html .= CommentWidget::widget(['user' => $user, 'comment' => $comment]);
        }

        return $this->asJson($html);
    }
}