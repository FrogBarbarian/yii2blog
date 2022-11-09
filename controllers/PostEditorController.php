<?php

declare(strict_types=1);

namespace app\controllers;

use app\models\Post;
use app\models\PostEditorForm;
use app\models\Statistic;
use app\models\Tag;
use app\models\TmpPost;
use app\models\UploadForm;
use Yii;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use yii\web\UploadedFile;
use yii\widgets\ActiveForm;

class PostEditorController extends AppController
{
    /**
     * Рендерит модальное окно для загрузки изображения.
     * @throws NotFoundHttpException
     */
    public function actionCreateImageUploadModalWindow(): string
    {
        $request = Yii::$app->getRequest();

        if (!$request->getIsAjax()) {
            throw new NotFoundHttpException();
        }

        $model = new UploadForm();

        return $this->renderAjax('_image-modal', ['model' => $model]);
    }

    /**
     * Страница редактирования поста.
     */
    public function actionEdit(string $id = null): string
    {
        $user = Yii::$app
            ->user
            ->getIdentity();

        if ($user === null) {
            throw new NotFoundHttpException();
        }

        $id = (int)$id;
        $post = Post::findOne($id);

        if ($post === null || $post->getAuthor() !== $user->getUsername()) {
            throw new NotFoundHttpException();
        }

        $model = new PostEditorForm();


        return $this->render('editor', [
            'id' => $id,
            'model' => $model,
            'post' => $post,
        ]);
    }

    /**
     * Страница создания поста.
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

        return $this->render('editor', ['model' => $model]);
    }

    /**
     * Загрузка изображения.
     */
    public function actionUploadImage(): Response
    {
        $request = Yii::$app->getRequest();

        if (!$request->isAjax) {
            throw new NotFoundHttpException();
        }

        $uploadForm = new UploadForm();
        $uploadForm->image = UploadedFile::getInstance($uploadForm, 'image');
        $uploadForm->signature = $request->post('UploadForm')['signature'];

        if ($uploadForm->upload()) {
            return $this->asJson([$uploadForm->imageName, $uploadForm->signature]);
        }

        return $this->asJson(ActiveForm::validate($uploadForm));
    }

    /**
     * Поиск по тегам.
     * @throws NotFoundHttpException
     */
    public function actionSearchTags(string $input): Response
    {
        $request = Yii::$app->getRequest();

        if (!$request->getIsAjax()) {
            throw new NotFoundHttpException();
        }

        $tags = Tag::find()
            ->byChars($input)
            ->limit(5)
            ->asArray()
            ->all();

        if ($tags === []) {
            return $this->asJson(false);
        }

        return $this->asJson($tags);
    }

    /**
     * TODO
     */
    public function actionCreate(): Response
    {
        $request = Yii::$app->getRequest();

        if (!$request->isAjax) {
            throw new NotFoundHttpException();
        }

        $user = Yii::$app
            ->user
            ->getIdentity();
        $model = new PostEditorForm();
        $model->load($request->post());

        if (isset($_REQUEST['ajax'])) {
            return $this->asJson(ActiveForm::validate($model));
        }

        if ($model->validate()) {
            if ($user->getIsAdmin() === true) {
                $post = new Post();
                $post
                    ->setTitle($model->title)
                    ->setBody($model->body)
                    ->setAuthor($user->getUsername())
                    ->setAuthorId($user->getId())
                    ->setTags($model->tags)
                    ->save();
                $statistics = Statistic::find()
                    ->byUsername($post->getAuthor())
                    ->one();
                $statistics
                    ->increasePosts()
                    ->save();
                Tag::checkWhenCreatePost($post->getTagsArray());

                return $this->asJson($post->getId());
            }

            $postTmp = new TmpPost();
            $postTmp
                ->setTitle($model->title)
                ->setBody($model->body)
                ->setTags($model->tags)
                ->setAuthor($user->getUsername())
                ->setAuthorId($user->getId())
                ->save();
            $message = 'Пост создан и отправлен на проверку администратору.';
            Yii::$app
                ->session
                ->setFlash('messageForIndex', $message);

            return $this->asJson(true);
        }


        return $this->asJson(false);
    }

    /**
     * TODO
     */
    public function actionUpdate(string $id): Response
    {
        $request = Yii::$app->getRequest();

        if (!$request->isAjax) {
            throw new NotFoundHttpException();
        }

        $user = Yii::$app
            ->user
            ->getIdentity();
        $model = new PostEditorForm();
        $id = (int)$id;
        $model->isNew = false;
        $model->load($request->post());

        if (isset($_REQUEST['ajax'])) {
            return $this->asJson(ActiveForm::validate($model));
        }

        if ($model->validate()) {
            if ($user->getIsAdmin() === true) {
                $post = Post::find()
                    ->byId($id)
                    ->one();
                $oldTags = $post->getOldTagsArray();
                $post
                    ->setTitle($model->title)
                    ->setBody($model->body)
                    ->setTags($model->tags)
                    ->save();
                $newTags = $post->getTagsArray();
                Tag::checkWhenUpdatePost($oldTags, $newTags);

                return $this->asJson($id);
            }

            $session = Yii::$app->session;
            $tmpPost = TmpPost::find()
                ->byUpdatedId($id)
                ->one();

            if ($tmpPost !== null) {
                $message = 'Пост уже редактировался и ожидает одобрения администратором.';
                $session->setFlash('postFlash', $message);

                return $this->asJson($id);
            }

            $post = Post::find()
                ->byId($id)
                ->one();
            $tmpPost = new TmpPost();
            $tmpPost
                ->setTitle($model->title)
                ->setBody($model->body)
                ->setTags($model->tags)
                ->setAuthor($user->getUsername())
                ->setAuthorId($user->getId())
                ->setOldTitle($post->getOldTitle())
                ->setOldBody($post->getOldBody())
                ->setOldTags($post->getOldTags())
                ->setIsNew(false)
                ->setUpdateId($post->getId())
                ->save();
            $message = 'Пост отредактирован и отправлен на проверку администратору.';
            $session->setFlash('postFlash', $message);

            return $this->asJson($id);
        }

        return $this->asJson(false);
    }
}
