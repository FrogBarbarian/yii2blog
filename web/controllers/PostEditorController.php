<?php

declare(strict_types=1);

namespace app\controllers;

use app\models\Post;
use app\models\PostEditorForm;
use app\models\Statistic;
use app\models\Tag;
use app\models\TmpPost;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use Yii;

class PostEditorController extends AppController
{
    /**
     * Сохраняет пост.
     * @throws NotFoundHttpException
     */
    public function actionSave(string $id = null): Response
    {
        $request = Yii::$app->getRequest();

        if (!$request->isAjax) {
            throw new NotFoundHttpException();
        }

        $user = Yii::$app->user->getIdentity();
        $postEditorForm = new PostEditorForm();
        $postEditorForm->isNew = $id === null;
        $id = $id === null ? $id : (int)$id;

        if ($postEditorForm->load(Yii::$app->request->post()) && $postEditorForm->validate()) {
            $session = Yii::$app->session;

            if ($user->getIsAdmin()) {
                if ($postEditorForm->isNew) {
                    $post = new Post();
                    $post
                        ->setTitle($postEditorForm->title)
                        ->setBody($postEditorForm->body)
                        ->setAuthor($user->getUsername())
                        ->setAuthorId($user->getId())
                        ->setTags($postEditorForm->tags)
                        ->save();
                    $statistics = Statistic::find()
                        ->byUsername($post->getAuthor())
                        ->one();
                    $statistics
                        ->increasePosts()
                        ->save();

                    foreach ($post->getTagsArray() as $tag) {
                        $tagObj = Tag::find()
                            ->byTag($tag)
                            ->one();

                        if ($tagObj === null) {
                            $tagObj = new Tag();
                            $tagObj
                                ->setTag($tag)
                                ->save();
                        } else {
                            $tagObj
                                ->increaseAmountOfUse()
                                ->save();
                        }
                    }
                } else {
                    $post = Post::find()
                        ->byId($id)
                        ->one();
                    $oldTags = $post->getOldTagsArray();
                    $post
                        ->setTitle($postEditorForm->title)
                        ->setBody($postEditorForm->body)
                        ->setTags($postEditorForm->tags)
                        ->save();
                    $newTags = $post->getTagsArray();
                    $unsetTags = array_diff($oldTags, $newTags);
                    $setTags = array_diff($newTags, $oldTags);

                    foreach ($unsetTags as $tag) {
                        $tagObj = Tag::find()
                            ->byTag($tag)
                            ->one();
                        $tagObj
                            ->decreaseAmountOfUse()
                            ->save();
                    }

                    foreach ($setTags as $tag) {
                        $tagObj = Tag::find()
                            ->byTag($tag)
                            ->one();

                        if ($tagObj === null) {
                            $tagObj = new Tag();
                            $tagObj
                                ->setTag($tag)
                                ->save();
                        } else {
                            $tagObj
                                ->increaseAmountOfUse()
                                ->save();
                        }
                    }
                }

                return $this->asJson(['success', $id]);
            }

            $postTmp = new TmpPost();

            if ($postEditorForm->isNew) {
                $postTmp
                    ->setTitle($postEditorForm->title)
                    ->setBody($postEditorForm->body)
                    ->setTags($postEditorForm->tags)
                    ->setAuthor($user->getUsername())
                    ->setAuthorId($user->getId())
                    ->save();
                $message = 'Пост создан и отправлен на проверку администратору.';
                $session->setFlash('messageForIndex', $message);

                return $this->asJson(['success', null]);
            }

            if (TmpPost::find()->byUpdatedId($id)->one() !== null) {
                $message = 'Пост уже редактировался и ожидает одобрения администратором.';
                $session->setFlash('postFlash', $message);

                return $this->asJson(['success', $id]);
            }

            $post = Post::find()
                ->byId($id)
                ->one();

            $postTmp
                ->setTitle($postEditorForm->title)
                ->setBody($postEditorForm->body)
                ->setTags($postEditorForm->tags)
                ->setAuthor($user->getUsername())
                ->setOldTitle($post->getOldTitle())
                ->setOldBody($post->getOldBody())
                ->setOldTags($post->getOldTags())
                ->setIsNew(false)
                ->setUpdateId($post->getId())
                ->save();
            $message = 'Пост отредактирован и отправлен на проверку администратору.';
            $session->setFlash('postFlash', $message);

            return $this->asJson(['success', $id]);
        }

        return $this->asJson($postEditorForm->errors);
    }
}
