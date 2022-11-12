<?php

declare(strict_types=1);

namespace app\controllers;

use app\models\Comment;
use app\models\CommentForm;
use app\models\Post;
use app\models\Statistic;
use src\helpers\ConstructHtml;
use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\Response;

/**
 * Обрабатывает ajax запросы, связанные с комментариями.
 */
class CommentAjaxController extends Controller
{
    /**
     * Добавляет комментарий к посту.
     *
     * @throws NotFoundHttpException
     */
    public
    function actionAddComment(): Response
    {
        $request = Yii::$app->getRequest();

        if (!$request->getIsAjax()) {
            throw new NotFoundHttpException();
        }

        $commentForm = new CommentForm();
        $postId = $request->post('postId');
        $post = Post::findOne($postId);
        $user = Yii::$app
            ->user
            ->getIdentity();

        if (!$user->getCanComment() || !$post->getIsCommentable()) {
            $commentForm->addError(
                'comment',
                'Что-то пошло не так, попробуйте обновить страницу',
            );

            return $this->asJson($commentForm->errors);
        }

        $commentForm->comment = $request->post('comment');

        if ($commentForm->validate()) {
            $comment = new Comment();
            $comment
                ->setPostId($post->getId())
                ->setAuthor($user->getUsername())
                ->setAuthorId($user->getId())
                ->setComment($commentForm->comment)
                ->save();
            Statistic::find()
                ->byUsername($user->getUsername())
                ->one()
                ->increaseComments()
                ->save();
            $post
                ->increaseCommentsAmount()
                ->save();

            return $this->asJson(true);
        }

        return $this->asJson($commentForm->errors);
    }

    /**
     * Обновляет цвет на кнопках лайк/дизлайк комментария.
     *
     * @throws NotFoundHttpException
     */
    public function actionUpdateRatingButtons(): Response
    {
        $request = Yii::$app->getRequest();

        if (!$request->getIsAjax()) {
            throw new NotFoundHttpException();
        }

        $userId = Yii::$app
            ->user
            ->getId();
        $commentId = $request->post('commentId');
        $comment = Comment::findOne($commentId);
        $liked = $comment->isUserAlreadyLikedComment($userId);
        $disliked = $comment->isUserAlreadyDislikedComment($userId);

        return $this->asJson([$liked, $disliked]);
    }

    /**
     * Сверяет текущий рейтинг комментариев с рейтингом в БД.
     *
     * @throws NotFoundHttpException
     */
    public function actionUpdateRating(): Response
    {
        $request = Yii::$app->getRequest();

        if (!$request->getIsAjax()) {
            throw new NotFoundHttpException();
        }

        $commentsData = $request->post('comments') ?? null;

        if ($commentsData === null) {
            return $this->asJson(false);
        }

        $newCommentData = [];
        $dataIsDiff = false;

        foreach ($commentsData as $commentData) {
            $id = $commentData['id'];
            $rating = (int)$commentData['rating'];
            $comment = Comment::findOne($id);
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
     * Ставит лайк||дизлайк комментарию.
     *
     * @throws NotFoundHttpException
     */
    public function actionLikeOrDislike(): Response
    {
        $request = Yii::$app->getRequest();

        if (!$request->isAjax) {
            throw new NotFoundHttpException();
        }

        $isLike = $request->post('isLike') === 'true';
        $commentId = $request->post('commentId');
        $userId = Yii::$app
            ->user
            ->getId();
        $comment = Comment::findOne($commentId);
        $ownerStatistics = Statistic::find()
            ->byUsername($comment->getAuthor())
            ->one();
        $isAlreadyLiked = $comment->isUserAlreadyLikedComment($userId);
        $isAlreadyDisliked = $comment->isUserAlreadyDislikedComment($userId);

        if ($isAlreadyDisliked) {
            $comment
                ->decreaseDislikes()
                ->removeDislikedByUserId($userId);
            $ownerStatistics->decreaseDislikes();
        }

        if ($isAlreadyLiked) {
            $comment
                ->decreaseLikes()
                ->removeLikedByUserId($userId);
            $ownerStatistics->decreaseLikes();
        }

        if ($isLike && !$isAlreadyLiked) {
            $comment
                ->increaseLikes()
                ->addLikedByUserId($userId);
            $ownerStatistics->increaseLikes();
        }

        if (!$isLike && !$isAlreadyDisliked) {
            $comment
                ->increaseDislikes()
                ->addDislikedByUserId($userId);
            $ownerStatistics->increaseDislikes();
        }

        $ownerStatistics
            ->updateRating()
            ->save();
        $comment
            ->updateRating()
            ->save();

        return $this->asJson(ConstructHtml::rating($comment->getRating()));
    }

    /**
     * Удаляет/восстанавливает комментарий.
     *
     * @throws NotFoundHttpException
     */
    public function actionDelete(): void
    {
        $request = Yii::$app->getRequest();

        if (!$request->isAjax) {
            throw new NotFoundHttpException();
        }

        $commentId = $request->post('id');
        $comment = Comment::findOne($commentId);
        $comment
            ->setIsDeleted(!$comment->getIsDeleted())
            ->save();
    }
}
