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

        $model = new CommentForm();
        $postId = $request->post('postId');
        $post = Post::findOne($postId);
        $user = Yii::$app
            ->user
            ->getIdentity();

        if (!$user->getCanComment() || !$post->getIsCommentable()) {
            $model->addError(
                'comment',
                'Что-то пошло не так, попробуйте обновить страницу',
            );

            return $this->asJson($model->errors);
        }

        $model->comment = $request->post('comment');

        if ($model->validate()) {
            $comment = new Comment();
            $comment
                ->setPostId($post->getId())
                ->setAuthor($user->getUsername())
                ->setAuthorId($user->getId())
                ->setComment($model->comment)
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

        return $this->asJson($model->errors);
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
     * Обновляет, если есть разница.
     *
     * @throws NotFoundHttpException
     */
    public function actionUpdateRating(): Response
    {
        $request = Yii::$app->getRequest();

        if (!$request->getIsAjax()) {
            throw new NotFoundHttpException();
        }

        $commentsOverallRating = (int)$request->post('overallRating');
        $postId = (int)$request->post('postId');
        $comments = Comment::find()->byPostId($postId)->filterNotDeleted()->all();
        $curCommentsOverallRank = 0;
        $data = [];

        foreach ($comments as $comment) {
            $curCommentsOverallRank += $comment->getRating();
        }

        if ($commentsOverallRating === $curCommentsOverallRank) {
            return $this->asJson(false);
        }

        foreach ($comments as $comment) {
            $data[$comment->getId()] = ConstructHtml::rating($comment->getRating());
        }

        return $this->asJson($data);
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
                ->removeDislikedCommentByUserId($userId);
            $ownerStatistics->decreaseDislikes();
        }

        if ($isAlreadyLiked) {
            $comment
                ->decreaseLikes()
                ->removeLikedCommentByUserId($userId);
            $ownerStatistics->decreaseLikes();
        }

        if ($isLike && !$isAlreadyLiked) {
            $comment
                ->increaseLikes()
                ->addLikedCommentByUserId($userId);
            $ownerStatistics->increaseLikes();
        }

        if (!$isLike && !$isAlreadyDisliked) {
            $comment
                ->increaseDislikes()
                ->addDislikedCommentByUserId($userId);
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
