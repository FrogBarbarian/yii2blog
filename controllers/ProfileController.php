<?php

declare(strict_types=1);

namespace app\controllers;

use app\models\Complaint;
use app\models\Message;
use app\models\Post;
use app\models\Statistic;
use app\models\TmpPost;
use app\models\User;
use Yii;
use yii\base\InvalidConfigException;
use yii\db\StaleObjectException;
use yii\web\NotFoundHttpException;
use yii\web\Response;

/**
 * Профиль пользователя.
 */
class ProfileController extends AppController
{
    /**
     * Профиль пользователя.
     * @throws NotFoundHttpException
     * @throws InvalidConfigException
     */
    public function actionProfile(string $tab = 'overview'): Response|string
    {
        $path = Yii::$app->request->getPathInfo();
        $user = Yii::$app->user->getIdentity();

        if ($path === 'profile') {
            if ($user === null) {
                return $this->redirect(USER_LOGIN);
            }

            $isOwn = true;
            $messages = Message::find()->all();

            if ($user->getIsAdmin()) {
                $tmpPosts = TmpPost::find()
                    ->all();
                $complaints = Complaint::find()
                    ->all();
            } else {
                $tmpPosts = TmpPost::find()
                    ->byAuthor($user->getUsername())
                    ->all();
                $complaints = Complaint::find()
                    ->bySenderId($user->getId())
                    ->all();
            }
        } else {
            $profileUserName = mb_strcut($path, 6);

            if ($user !== null && $profileUserName === $user->getUsername()) {
                return $this->redirect('/profile');
            }

            $user = User::find()
                ->byUsername($profileUserName)
                ->one();

            if ($user === null) {
                throw new NotFoundHttpException();
            }

            $visitor = Yii::$app->user->getIdentity();
            $isOwn = false;
            $tab = 'overview';
        }

        $posts = Post::find()
            ->byAuthor($user->getUsername())
            ->orderDescById()
            ->limit(5)
            ->all();
        $statistics = Statistic::find()
            ->byUsername($user->getUsername())
            ->one();

        return $this->render('profile', [
            'user' => $user,
            'visitor' => $visitor ?? null,
            'posts' => $posts,
            'tmpPosts' => $tmpPosts ?? null,
            'complaints' => $complaints ?? null,
            'statistics' => $statistics,
            'messages' => $messages ?? null,
            'isOwn' => $isOwn,
            'tab' => $tab,
        ]);
    }

    /**
     * Получает и рендерит список сообщений.
     * @throws NotFoundHttpException
     */
    public function actionGetMails(): string
    {
        $request = Yii::$app->getRequest();

        if (!$request->isAjax && !isset($_REQUEST['ajax'])) {
            throw new NotFoundHttpException();
        }

        $event = $request->post('ajax')['event'];
        $page = (int)$request->post('ajax')['page'];
        $user = Yii::$app
            ->user
            ->getIdentity();
        $status = null;
        $sender = null;
        $recipient = null;
        $isSender = null;

        switch ($event) {
            case 'sent':
                $status = 'sent';
                $sender = $user->getUsername();
                $isSender = true;
                break;
            case 'inbox':
                $status = 'received';
                $recipient = $user->getUsername();
                $isSender = false;
                break;
            case 'spam':
                $status = 'spam';
                $isSender = false;
                $recipient = $user->getUsername();
                break;
        }

        $limit = 20;
        $offset = $limit * ($page - 1);
        $messages = Message::find()
            ->byStatus($status, $isSender)
            ->sentFrom($sender)
            ->sentFor($recipient)
            ->orderById()
            ->all();
        $pages = (int)ceil(count($messages) / $limit);
        $messages = array_slice($messages, $offset, $limit);

        return $this->renderAjax("tabs/mailbox/_$event", [
            'messages' => $messages,
            'pages' => $pages,
            'page' => $page,
            ]);
    }

    /**
     * Сообщение.
     * @throws InvalidConfigException
     * @throws NotFoundHttpException
     */
    public function actionMessage(): string
    {
        $user = Yii::$app
            ->user
            ->getIdentity();

        if ($user === null) {
            throw new NotFoundHttpException();
        }

        $path = Yii::$app
            ->request
            ->getPathInfo();
        preg_match('/\d+/', $path, $matches);

        if (empty($matches)) {
            throw new NotFoundHttpException();
        }

        $id = (int)$matches[0];
        $message = Message::find()
            ->byId($id)
            ->one();
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

        return $this->render('message', ['message' => $message, 'userIsSender' => $userIsSender]);
    }

    /**
     * Меняет статус письма на 'удалено'.
     * @throws NotFoundHttpException
     * @throws \Throwable
     */
    public function actionDeleteMessage(): Response
    {
        $request = Yii::$app->getRequest();

        if (!$request->isAjax) {
            throw new NotFoundHttpException();
        }

        $messageId = (int)$request->post('ajax')['id'];
        $isSender = $request->post('ajax')['isSender'] === 'true';
        $message = Message::find()
            ->byId($messageId)
            ->one();

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
     * @throws NotFoundHttpException
     */
    public function actionSpamMessage(): Response
    {
        $request = Yii::$app->getRequest();

        if (!$request->isAjax) {
            throw new NotFoundHttpException();
        }

        $messageId = (int)$request->post('ajax')['id'];
        $message = Message::find()
            ->byId($messageId)
            ->one();
        $message
            ->setRecipientStatus($message->getRecipientStatus() === 'spam' ? 'received' : 'spam')
            ->save();

        return $this->asJson('/profile?tab=mailbox');
    }
}
