<?php

declare(strict_types=1);

namespace app\controllers;

use app\models\Comment;
use app\models\Post;
use app\models\User;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use Yii;

/**
 * Контроллер сайта.
 */
class SiteController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function actions(): array
    {
        return [
            'error' => [
                'class' => \yii\web\ErrorAction::class,
            ],
        ];
    }

    /**
     * Перенаправляет на комментарий.
     *
     * @throws NotFoundHttpException
     */
    public function actionFindComment(string $id = null): Response
    {
        $id = (int)$id;
        $comment = Comment::findOne($id);

        if ($comment === null) {
            throw new NotFoundHttpException();
        }

        $postId = $comment->getPostId();

        return $this->redirect("/post?id=$postId#comment$id");
    }

    /**
     * Перенаправляет на профиль пользователя.
     *
     * @throws NotFoundHttpException
     */
    public function actionFindProfile(string $id = null): Response
    {
        $id = (int)$id;
        $user = User::findOne($id);

        if ($user !== null) {
            return $this->redirect("/users/{$user->getUsername()}");
        }

        throw new NotFoundHttpException();
    }

    /**
     * Находит и открывает случайный пост.
     *
     * @throws NotFoundHttpException
     */
    public function actionRandom(): Response
    {
        $post = Post::find()
            ->random()
            ->one();

        return $this->redirect(['post/post', 'id' => $post->getId()]);
    }

    /**
     * Предлагает результаты в строке поиска.
     *
     * @throws NotFoundHttpException
     */
    public function actionSearchSuggestion(string $input): Response
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
     * Страница с информацией о сайте и формой обратной связи.
     */
    public function actionAbout(): string
    {
        $request = Yii::$app->getRequest();

        if ($request->isAjax) {
            $feed = $request->post('feed');
            $username = Yii::$app
                ->user
                ->getIdentity()
                ->getUsername();

            Yii::$app
                ->mailer
                ->compose('feedback', ['feed' => $feed, 'username' => $username])
                ->setFrom(NO_REPLY_MAIL)
                ->setTo(OWNER_EMAIL)
                ->setSubject('Обратная связь')
                ->send();
        }

        return $this->render('about');
    }
}
