<?php

namespace app\controllers;

use app\models\Post;
use app\models\Statistics;
use src\helpers\ConstructHtml;
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
    public function actionLikePost(): Response
    {
        $request = Yii::$app->getRequest();

        if (!Yii::$app->getRequest()->getIsAjax() && isset($_REQUEST['ajax'])) {
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

        return $this->asJson(ConstructHtml::rating($post->getRating()));


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
        $request = Yii::$app->getRequest();

        if (!$request->getIsAjax() && !isset($_REQUEST['ajax'])) {
            throw new NotFoundHttpException();
        }

        $postId = $request->get('ajax')['postId'];
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

        $postId = $request->get('ajax')['postId'];
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

        $postId = $request->get('ajax')['postId'];
        $post = Post::find()
            ->byId($postId)
            ->one();
        $params = "class='alert alert-secondary text-center text-danger' role='alert'";
        $text = 'Комментарии запрещены.';
        $html = !$post->getIsCommentable() ? '' : ConstructHtml::any('div', $params, $text);

        return $this->asJson($html);
    }

    //TODO: Need?
    public function actionCommentForm()
    {
        $request = Yii::$app->getRequest();

        if (!$request->getIsAjax() && !isset($_REQUEST['ajax'])) {
            throw new NotFoundHttpException();
        }

        $postId = $request->get('ajax')['postId'];
        $post = Post::find()
            ->byId($postId)
            ->one();
//        $html = $post->getIsCommentable() ? '' : require 'widgets/comment-field.php';

        return $this->asJson('1');
    }
}
