<?php

namespace app\controllers;

use app\models\Comment;
use app\models\Post;
use app\models\Statistics;
use src\helpers\ConstructHtml;
use src\helpers\NormalizeData;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use Yii;

class PostInterfaceController extends AppController
{
    /**
     * Находит случайный пост.
     * @throws NotFoundHttpException
     */
    public function actionRandomPost(): Response
    {
        if (!Yii::$app->getRequest()->getIsAjax()) {
            throw new NotFoundHttpException();
        }

        $post = Post::find()
            ->random()
            ->one();

        return $this->asJson('/post?id=' . $post->getId());
    }

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

        $userId = Yii::$app->session['id'];
        $postId = $request->post('ajax')['postId'];
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

        $userId = Yii::$app->session['id'];
        $postId = $request->post('ajax')['postId'];
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
    public function actionUpdateRatingButtons(): Response
    {
        $request = Yii::$app->getRequest();

        if (!$request->getIsAjax() && !isset($_REQUEST['ajax'])) {
            throw new NotFoundHttpException();
        }

        $userId = Yii::$app->session['id'];
        $postId = $request->post('ajax')['postId'];
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

        $postId = $request->post('ajax')['postId'];
        $post = Post::find()
            ->byId($postId)
            ->one();

        return $this->asJson(ConstructHtml::rating($post->getRating()));
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

        $postId = $request->post('ajax')['postId'];
        $post = Post::find()
            ->byId($postId)
            ->one();
        $post
            ->setIsCommentable(!$post->getisCommentable())
            ->save();
        $buttonName = $post->getisCommentable() ? 'comment-enabled' : 'comment-disabled';
        $html = ConstructHtml::img($buttonName, $buttonName);

        return $this->asJson($html);
    }

    /**
     * Отрисовывает (если комментарии запрещены) alert поле с информацией об этом.
     * @throws NotFoundHttpException
     */
    public function actionCommentsPermissions(): Response
    {
        $request = Yii::$app->getRequest();

        if (!$request->getIsAjax() && !isset($_REQUEST['ajax'])) {
            throw new NotFoundHttpException();
        }

        $postId = $request->post('ajax')['postId'];
        $post = Post::find()
            ->byId($postId)
            ->one();
        $params = "class='alert alert-secondary text-center text-danger' role='alert'";
        $text = 'Комментарии запрещены.';
        $html = !$post->getIsCommentable() ? '' : ConstructHtml::any('div', $params, $text);

        return $this->asJson($html);
    }

    /**
     * Обновляет список комментариев.
     * @throws NotFoundHttpException
     */
    public function actionUpdateComments()
    {
        $request = Yii::$app->getRequest();

        if (!$request->getIsAjax() && !isset($_REQUEST['ajax'])) {
            throw new NotFoundHttpException();
        }

        $postId = $request->post('ajax')['postId'];
        $comments = Comment::find()
            ->byPostId($postId)
            ->orderAscById()
            ->all();

        return $this->asJson([ConstructHtml::comments($comments), count($comments) . ' ' . NormalizeData::wordForm(count($comments), 'комментариев', 'комментарий', 'комментария')]);
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

        $commentId = $request->post('ajax')['commentId'];
        $userId = Yii::$app->session['id'];
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

        $commentId = $request->post('ajax')['commentId'];
        $userId = Yii::$app->session['id'];
        $comment = Comment::find()
            ->byId($commentId)
            ->one();
        $ownerStatistics = Statistics::find()
            ->byLogin($comment->getAuthor())
            ->one();

        if ($comment->isUserLikeIt($userId)) {
            $comment
                ->decreaseLikes()
                ->removeLikedByUserId($userId)
                ->save();
            $ownerStatistics
                ->decreaseLikes()
                ->save();
        }

        if ($comment->isUserDislikeIt($userId)) {
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
}
