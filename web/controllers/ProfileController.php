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

    public function actionGetMails()
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

        switch ($event) {
            case 'sent':
                $status = 'sent';
                $sender = $user->getUsername();
                $recipient = null;
                break;
            case 'inbox':
                $status = 'sent';
                $sender = null;
                $recipient = $user->getUsername();
                break;
            case 'draft':
                $status = 'draft';
                $sender = $user->getUsername();
                $recipient = null;
                break;
        }

        $limit = 20;
        $offset = $limit * ($page - 1);
        $messages = Message::find()
            ->byStatus($status ?? null)
            ->sentFrom($sender ?? null)
            ->sentFor($recipient ?? null)
            ->orderById()
            ->all();
        $pages = (int)ceil(count($messages) / $limit);
        $messages = array_slice($messages, $offset, $limit);


        return $this->renderAjax($event, ['messages' => $messages, 'pages' => $pages, 'page' => $page]);
    }
}
