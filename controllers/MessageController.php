<?php

declare(strict_types=1);

namespace app\controllers;

use app\models\Message;
use app\models\MessageForm;
use app\models\User;
use yii\db\StaleObjectException;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use Yii;
use yii\web\Response;
use yii\widgets\ActiveForm;

/**
 * Контроллер сообщения.
 */
class MessageController extends Controller
{
    /**
     * Сообщение.
     *
     * @throws NotFoundHttpException
     */
    public function actionIndex(string $id = null): string
    {
        $user = Yii::$app
            ->user
            ->getIdentity();

        if ($user === null) {
            throw new NotFoundHttpException();
        }

        $id = (int)$id;
        $message = Message::findOne($id);
        $username = $user->getUsername();
        $userIsSender = $message->getSenderUsername() === $username;
        $userIsRecipient = $message->getRecipientUsername() === $username;
        $senderStatus = $message->getSenderStatus();
        $recipientStatus = $message->getRecipientStatus();
        $isAccess = ($userIsSender && $senderStatus !== 'deleted') ||
            ($userIsRecipient && $recipientStatus !== ' deleted');

        if ($isAccess === false) {
            throw new NotFoundHttpException();
        }

        if ($userIsRecipient) {
            $message
                ->setIsRead(true)
                ->save();
        }

        return $this->render('/user/message', ['message' => $message, 'userIsSender' => $userIsSender]);
    }

    /**
     * Меняет статус письма на 'удалено'.
     *
     * @throws NotFoundHttpException
     * @throws \Throwable
     * @throws StaleObjectException
     */
    public function actionDelete(): Response
    {
        $request = Yii::$app->getRequest();

        if (!$request->isAjax) {
            throw new NotFoundHttpException();
        }

        $messageId = $request->post('id');
        $isSender = $request->post('isSender') === 'true';
        $message = Message::findOne($messageId);

        if ($isSender) {
            $message
                ->setSenderStatus('deleted')
                ->save();
        } else {
            $message
                ->setRecipientStatus('deleted')
                ->save();
        }

        if ($message->getRecipientStatus() === 'deleted' && $message->getSenderStatus() === 'deleted') {
            $message->delete();
        }

        return $this->asJson('/profile?tab=mailbox');
    }

    /**
     * Меняет статус письма на 'спам'.
     *
     * @throws NotFoundHttpException
     */
    public function actionSpam(): Response
    {
        $request = Yii::$app->getRequest();

        if (!$request->isAjax) {
            throw new NotFoundHttpException();
        }

        $messageId = $request->post('id');
        $message = Message::findOne($messageId);
        $message
            ->setRecipientStatus($message->getRecipientStatus() === 'spam' ? 'received' : 'spam')
            ->save();

        return $this->asJson('/profile?tab=mailbox');
    }

    /**
     * Создает окно для отправки сообщения.
     *
     * @throws NotFoundHttpException
     */
    public function actionCreateModalWindow(): string
    {
        $request = Yii::$app->getRequest();

        if (!$request->getIsAjax()) {
            throw new NotFoundHttpException();
        }

        $model = new MessageForm();

        return $this->renderAjax('/ui/_message-modal', ['model' => $model]);
    }

    /**
     * Отправляет сообщение.
     *
     * @throws NotFoundHttpException
     */
    public function actionSend(): Response
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
     * Получает список получателей для сообщения.
     *
     * @throws NotFoundHttpException
     */
    public function actionGetRecipients(string $input): Response
    {
        $request = Yii::$app->request;

        if (!$request->getIsAjax()) {
            throw new NotFoundHttpException();
        }

        $users = User::find()
            ->byChars($input)
            ->limit(5)
            ->asArray()
            ->all();

        if ($users === []) {
            return $this->asJson(false);
        }

        return $this->asJson($users);
    }
}
