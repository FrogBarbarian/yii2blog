<?php

declare(strict_types=1);

namespace app\controllers;

use app\models\Post;
use app\models\PostEditorForm;
use app\models\Statistic;
use app\models\Tag;
use app\models\TmpPost;
use app\models\UploadForm;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use Yii;
use yii\web\Response;
use yii\web\UploadedFile;
use yii\widgets\ActiveForm;

/**
 * Обрабатывает ajax запросы со страницы редактора поста.
 */
class PostEditorAjaxController extends Controller
{
    /**
     * Рендерит модальное окно для загрузки изображения.
     *
     * @throws NotFoundHttpException
     */
    public function actionCreateImageUploadModalWindow(): string
    {
        $request = Yii::$app->getRequest();

        if (!$request->getIsAjax()) {
            throw new NotFoundHttpException();
        }

        $model = new UploadForm();

        return $this->renderAjax('/post-editor/_image-modal', ['model' => $model]);
    }

    /**
     * Загружает изображение.
     *
     * @throws NotFoundHttpException
     */
    public function actionUploadImage(): Response
    {
        $request = Yii::$app->getRequest();

        if (!$request->isAjax) {
            throw new NotFoundHttpException();
        }

        $model = new UploadForm();
        $model->image = UploadedFile::getInstance($model, 'image');
        $model->signature = $request->post('UploadForm')['signature'];

        if ($model->upload()) {
            return $this->asJson([$model->imageName, $model->signature]);
        }

        return $this->asJson(false);
    }

    /**
     * Поиск тегов для добавления.
     *
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
     * Создает новый пост.
     *
     * @throws NotFoundHttpException
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
                Statistic::find()
                    ->byUsername($post->getAuthor())
                    ->one()
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
     * Обновляет редактируемый пост.
     *
     * @throws NotFoundHttpException
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
                $post = Post::findOne($id);
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

            $post = Post::findOne($id);
            $tmpPost = new TmpPost();
            $tmpPost
                ->setTitle($model->title)
                ->setBody($model->body)
                ->setTags($model->tags)
                ->setAuthor($user->getUsername())
                ->setAuthorId($user->getId())
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
