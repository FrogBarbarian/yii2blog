<?php

declare(strict_types=1);

namespace app\controllers;

use app\models\Complaint;
use app\models\ComplaintForm;
use app\models\Post;
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


        return $this->renderAjax('complaint-modal', [
            'complaintForm' => $complaintForm,
            'objectType' => $objectType,
            'objectId' => $objectId,
        ]);
    }

    /**
     * Отправка жалобы.
     * @throws NotFoundHttpException
     * @throws \Throwable
     */
    public function actionSendComplaint(): Response
    {
        $request = Yii::$app->getRequest();

        if (!$request->isAjax) {
            throw new NotFoundHttpException();
        }

        $complaintForm = new ComplaintForm();

        if ($complaintForm->load($request->post()) && $complaintForm->validate()) {
            $sender = Yii::$app
                ->user
                ->getIdentity();
            $content = $request->post('ComplaintForm')['complaint'];
            $objectType = $request->post('ComplaintForm')['objectType'];
            $objectId = (int)$request->post('ComplaintForm')['objectId'];
            $complaint = new Complaint();
            $complaint
                ->setObject($objectType)
                ->setObjectId($objectId)
                ->setSenderId($sender->getId())
                ->setSenderUsername($sender->getUsername())
                ->setComplaint($content)
                ->save();

            return $this->asJson(true);
        }

        return $this->asJson($complaintForm->errors);
    }

    /**
     * Предлагает статьи для открытия.
     * @throws NotFoundHttpException
     */
    public function actionSearchSuggest(string $input): Response
    {
        $request = Yii::$app->getRequest();

        if (!$request->getIsAjax()) {
            throw new NotFoundHttpException();
        }

        $post = Post::find()
            ->postHasWords($input)
            ->limit(5)
            ->all();

        if ($post === []) {
            return $this->asJson(false);
        }

        return $this->asJson($post);
    }
}
