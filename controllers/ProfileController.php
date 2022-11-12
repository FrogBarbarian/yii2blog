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
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\Response;

/**
 * Контроллер профиля пользователя.
 */
class ProfileController extends Controller
{
    /**
     * Профиль пользователя.
     *
     * @throws NotFoundHttpException
     * @throws InvalidConfigException
     */
    public function actionProfile(string $tab = 'overview'): Response|string
    {
        $path = Yii::$app
            ->request
            ->getPathInfo();
        $user = Yii::$app
            ->user
            ->getIdentity();

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

            $visitor = Yii::$app
                ->user
                ->getIdentity();
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
}
