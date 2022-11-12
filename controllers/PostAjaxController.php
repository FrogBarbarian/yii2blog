<?php

declare(strict_types=1);

namespace app\controllers;

use app\components\CommentWidget;
use app\models\Comment;
use app\models\Post;
use app\models\Statistic;
use app\models\Tag;
use src\helpers\ConstructHtml;
use src\helpers\NormalizeData;
use yii\db\StaleObjectException;
use yii\web\Controller;
use Yii;
use yii\web\NotFoundHttpException;
use yii\web\Response;

/**
 * Обрабатывает ajax запросы со страницы поста.
 */
class PostAjaxController extends Controller
{
    /**
     * Обновляет комментарии к посту.
     *
     * @throws NotFoundHttpException
     * @throws \Exception
     */
    public function actionUpdateComments(): Response
    {
        $request = Yii::$app->getRequest();

        if (!$request->getIsAjax()) {
            throw new NotFoundHttpException();
        }

        $postId = (int)$request->post('postId');
        $curCommentsAmount = (int)$request->post('curCommentsAmount');
        $post = Post::findOne($postId);
        $postCommentsAmount = $post->getCommentsAmount();

        if ($curCommentsAmount === $postCommentsAmount) {
            return $this->asJson(false);
        }

        $diff = $postCommentsAmount - $curCommentsAmount;
        $user = Yii::$app
            ->user
            ->getIdentity();
        $comments = Comment::find()
            ->byPostId($postId)
            ->orderDescById()
            ->limit($diff)
            ->all();
        $commentsHtml = '';

        foreach ($comments as $comment) {
            $commentsHtml .= CommentWidget::widget(['comment' => $comment, 'user' => $user]);
        }

        $wordForm = NormalizeData::wordForm(
            $postCommentsAmount, 'комментариев',
            'комментарий',
            'комментария',
        );
        $commentsAmountHtml = "$postCommentsAmount $wordForm";

        return $this->asJson(['comments' => $commentsHtml, 'amount' => $commentsAmountHtml]);
    }

    /**
     * Удаляет пост.
     *
     * @throws NotFoundHttpException
     * @throws StaleObjectException
     * @throws \Throwable
     */
    public function actionDelete(): void
    {
        $request = Yii::$app->getRequest();

        if (!$request->isAjax) {
            throw new NotFoundHttpException();
        }

        $postId = (int)$request->post('postId');
        $post = Post::findOne($postId);
        Statistic::find()
            ->byUsername($post->getAuthor())
            ->one()
            ->decreasePosts()
            ->decreaseViews($post->getViews())
            ->decreaseLikes($post->getLikes())
            ->decreaseDislikes($post->getDislikes())
            ->updateRating()
            ->save();
        $comments = Comment::find()
            ->byPostId($postId)
            ->all();

        foreach ($post->getTagsArray() as $tag) {
            Tag::find()
                ->byTag($tag)
                ->one()
                ->decreaseAmountOfUse()
                ->save();
        }

        foreach ($comments as $comment) {
            Statistic::find()
                ->byUsername($comment->getAuthor())
                ->one()
                ->decreaseLikes($comment->getLikes())
                ->decreaseDislikes($comment->getDislikes())
                ->decreaseComments()
                ->updateRating()
                ->save();
            $comment->delete();
        }

        Yii::$app
            ->session
            ->setFlash('messageForIndex', "Пост '<b>{$post->getTitle()}</b>' удален.");
        $post->delete();
    }

    /**
     * Запрещает||разрешает комментирование поста.
     *
     * @throws NotFoundHttpException
     */
    public function actionCommentPermissions(): Response
    {
        $request = Yii::$app->getRequest();

        if (!$request->getIsAjax()) {
            throw new NotFoundHttpException();
        }

        $postId = $request->post('postId');
        $post = Post::findOne($postId);
        $post
            ->setIsCommentable(!$post->getisCommentable())
            ->save();
        $isCommentable = $post->getIsCommentable();

        return $this->asJson($isCommentable);
    }

    /**
     * Ставит лайк||дизлайк посту.
     *
     * @throws NotFoundHttpException
     */
    public function actionLikeOrDislike(): void
    {
        $request = Yii::$app->getRequest();

        if (!$request->getIsAjax()) {
            throw new NotFoundHttpException();
        }

        $isLike = $request->post('isLike') === 'true';
        $postId = (int)$request->post('postId');
        $userId = Yii::$app
            ->user
            ->getId();
        $post = Post::findOne($postId);
        $ownerStatistics = Statistic::find()
            ->byUsername($post->getAuthor())
            ->one();
        $isAlreadyLiked = $post->isUserAlreadyLikedPost($userId);
        $isAlreadyDisliked = $post->isUserAlreadyDislikedPost($userId);

        if ($isAlreadyDisliked) {
            $post
                ->decreaseDislikes()
                ->bateDislikedByUserId($userId);
            $ownerStatistics->decreaseDislikes();
        }

        if ($isAlreadyLiked) {
            $post
                ->decreaseLikes()
                ->bateLikedByUserId($userId);
            $ownerStatistics->decreaseLikes();
        }

        if ($isLike && !$isAlreadyLiked) {
            $post
                ->increaseLikes()
                ->addLikedByUserId($userId);
            $ownerStatistics->increaseLikes();
        }

        if (!$isLike && !$isAlreadyDisliked) {
            $post
                ->increaseDislikes()
                ->addDislikedByUserId($userId);
            $ownerStatistics->increaseDislikes();
        }

        $post
            ->updateRating()
            ->save();
        $ownerStatistics
            ->updateRating()
            ->save();
    }

    /**
     * Обновляет кнопки лайк/дизлайк поста.
     *
     * @throws NotFoundHttpException
     */
    public function actionUpdatePostRatingButtons(): Response
    {
        $request = Yii::$app->getRequest();

        if (!$request->getIsAjax()) {
            throw new NotFoundHttpException();
        }

        $userId = Yii::$app
            ->user
            ->getId();
        $postId = $request->post('postId');
        $post = Post::findOne($postId);
        $liked = $post->isUserAlreadyLikedPost($userId);
        $disliked = $post->isUserAlreadyDislikedPost($userId);

        return $this->asJson([$liked, $disliked]);
    }

    /**
     * Обновляет рейтинг на странице поста.
     *
     * @throws NotFoundHttpException
     */
    public function actionUpdatePostRating(): Response
    {
        $request = Yii::$app->getRequest();

        if (!$request->getIsAjax()) {
            throw new NotFoundHttpException();
        }

        $postId = $request->post('postId');
        $curRating = (int)$request->post('curRating');
        $post = Post::findOne($postId);

        if ($post->getRating() === $curRating) {
            return $this->asJson(false);
        }

        return $this->asJson(ConstructHtml::rating($post->getRating()));
    }
}
