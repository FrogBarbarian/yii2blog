<?php

namespace app\controllers;

use app\models\Comment;
use app\models\Complaint;
use app\models\ComplaintForm;
use app\models\Post;
use app\models\Statistics;
use src\helpers\ConstructHtml;
use src\helpers\NormalizeData;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use Yii;

class UIController extends AppController
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

    /**
     * Создает окно для отправки жалобы.
     * @throws NotFoundHttpException
     */
    public function actionCreateComplaintWindow(): string
    {
        $request = Yii::$app->getRequest();

        if (!$request->isAjax) {
            throw new NotFoundHttpException();
        }

        $complaintForm = new ComplaintForm();
        $objectType = $request->post('ajax')['objectType'];
        $objectId = $request->post('ajax')['objectId'];
        $senderId = $request->post('ajax')['senderId'];

        return $this->renderAjax('complaint-window', [
            'complaintForm' => $complaintForm,
            'objectType' => $objectType,
            'objectId' => $objectId,
            'subjectId' => $senderId,
        ]);
    }

    /**
     * Отправка жалобы.
     * @throws NotFoundHttpException
     */
    public function actionSendComplaint(): Response
    {
        $request = Yii::$app->getRequest();

        if (!$request->isAjax) {
            throw new NotFoundHttpException();
        }

        $model = new ComplaintForm();

        if ($model->load($request->post()) && $model->validate()) {
            $content = $request->post('ComplaintForm')['complaint'];
            $objectType = $request->post('ComplaintForm')['objectType'];
            $objectId = $request->post('ComplaintForm')['objectId'];
            $subjectId = $request->post('ComplaintForm')['subjectId'];
            $complaint = new Complaint();
            $complaint
                ->setObject($objectType)
                ->setObjectId($objectId)
                ->setSenderId($subjectId)
                ->setComplaint($content)
                ->save();

            return $this->asJson(true);
        }

        return $this->asJson($model->errors);
    }
}
