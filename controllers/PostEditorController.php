<?php

declare(strict_types=1);

namespace app\controllers;

use app\models\Post;
use app\models\PostEditorForm;
use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

/**
 * Редактор поста.
 */
class PostEditorController extends Controller
{
    /**
     * Страница создания поста.
     *
     * @throws NotFoundHttpException
     */
    public function actionNew(): string
    {
        $user = Yii::$app
            ->user
            ->getIdentity();

        if ($user === null) {
            throw new NotFoundHttpException();
        }

        $model = new PostEditorForm();

        return $this->render('editor', ['model' => $model, 'isNew' => true]);
    }

    /**
     * Страница редактирования поста.
     *
     * @throws NotFoundHttpException
     */
    public function actionEdit(string $id = null): string
    {
        $user = Yii::$app
            ->user
            ->getIdentity();

        if ($user === null || $id === null) {
            throw new NotFoundHttpException();
        }

        $id = (int)$id;
        $post = Post::findOne($id);

        if ($post === null || $post->getAuthor() !== $user->getUsername()) {
            throw new NotFoundHttpException();
        }

        $model = new PostEditorForm();

        return $this->render('editor', [
            'model' => $model,
            'post' => $post,
            'isNew' => false,
        ]);
    }
}
