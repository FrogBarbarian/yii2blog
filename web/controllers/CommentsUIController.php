<?php

declare(strict_types=1);

namespace app\controllers;

use app\models\Comment;
use app\models\Statistics;
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
        $userId = (int)Yii::$app->session['id'];
        $comment = Comment::find()
            ->byId($commentId)
            ->one();
        $ownerStatistics = Statistics::find()
            ->byLogin($comment->getAuthor())
            ->one();

        if ($comment->isUserDislikeIt($userId)) {
            $comment
                ->decreaseDislikes()
                ->removeDislikedByUserId($userId)
                ->save();
            $ownerStatistics
                ->decreaseDislikes()
                ->save();
        }

        if ($comment->isUserLikeIt($userId)) {
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
}
