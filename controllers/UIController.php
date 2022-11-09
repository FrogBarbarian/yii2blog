<?php

declare(strict_types=1);

namespace app\controllers;

use app\models\Complaint;
use app\models\ComplaintForm;
use app\models\Message;
use app\models\MessageForm;
use app\models\Post;
use app\models\User;
use Yii;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use yii\widgets\ActiveForm;

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


        return $this->renderAjax('/ui/complaint-modal', [
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

        $model = new ComplaintForm();
        $model->load($request->post());

        if (isset($_REQUEST['ajax'])) {
            return $this->asJson(ActiveForm::validate($model));
        }

        if ($model->validate()) {
            $sender = Yii::$app
                ->user
                ->getIdentity();
            $complaint = new Complaint();
            $complaint
                ->setObject($model->objectType)
                ->setObjectId($model->objectId)
                ->setSenderId($sender->getId())
                ->setSenderUsername($sender->getUsername())
                ->setComplaint($model->complaint)
                ->save();

            return $this->asJson(true);
        }

        return $this->asJson(false);
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

    /**
     * TODO: COMMENT
     */
    public function actionMessageModal(): string
    {
        $request = Yii::$app->getRequest();

        if (!$request->getIsAjax()) {
            throw new NotFoundHttpException();
        }

        $messageForm = new MessageForm();

        return $this->renderAjax('/ui/message-modal', ['messageForm' => $messageForm]);
    }

    /**
     * @return Response TODO: COMM
     */
    public function actionSendMessage()
    {
        $request = Yii::$app->getRequest();

        if (!$request->isAjax) {
            throw new NotFoundHttpException();
        }

        $model = new MessageForm();
        $model->load($request->post());

        if (isset($_REQUEST['ajax'])) {
            return $this->asJson(ActiveForm::validate($model));
        }

        if ($model->validate()) {
            $message = new Message();
            $user = Yii::$app
                ->user
                ->getIdentity();
            $message
                ->setSenderUsername($user->getUsername())
                ->setRecipientUsername($model->recipientUsername)
                ->setSubject($model->subject)
                ->setContent($model->content)
                ->save();

            return $this->asJson(true);
        }

        return $this->asJson(false);
    }

    /**
     * @return Response TODO: COMM
     */
    public function actionGetUsers(string $data)
    {
        $request = Yii::$app->request;

        if (!$request->getIsAjax()) {
            throw new NotFoundHttpException();
        }

        $users = User::find()
            ->byChars($data)
            ->limit(5)
            ->asArray()
            ->all();

        if ($users === []) {
            return $this->asJson(false);
        }

        return $this->asJson($users);
    }
}
