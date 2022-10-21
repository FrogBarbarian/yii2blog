<?php

declare(strict_types=1);

namespace app\controllers;

use app\models\Complaint;
use app\models\Tag;
use Psr\SimpleCache\InvalidArgumentException;
use Yii;
use yii\db\StaleObjectException;
use yii\web\NotFoundHttpException;
use yii\web\Response;

class AdminUIController extends AppController
{
    /**
     * Удаляет тег.
     * @throws \Throwable
     * @throws InvalidArgumentException
     */
    public function actionDeleteTag(string $id)
    {
        if (!Yii::$app->request->isAjax) {
            throw new NotFoundHttpException();
        }

        $id = (int)$id;
        Tag::find()
            ->byId($id)
            ->one()
            ->delete();
        $cache = Yii::$app->cache;
        $cache->delete('tags');
    }

    /**
     * Удаляет жалобу.
     * @throws \Throwable
     * @throws StaleObjectException
     */
    public function actionDeleteComplaint(string $id)
    {
        if (!Yii::$app->request->isAjax) {
            throw new NotFoundHttpException();
        }

        $id = (int)$id;
        Complaint::find()
            ->byId($id)
            ->one()
            ->delete();
        //TODO: Пользователь получает оповещение в ЛС
    }

    /**
     * Получает объекты для админ-панели.
     * @throws NotFoundHttpException
     */
    public function actionGetObjects(string $model, string $offset, string $page, string $sortParam, string $sortOrder): Response
    {
        if (!Yii::$app->request->isAjax) {
            throw new NotFoundHttpException();
        }

        $page = (int)$page;
        $offset = (int)$offset;
        $sortOrder = (int)$sortOrder;
        $users = ("app\models\\$model")::find()
            ->orderBy([$sortParam => $sortOrder])
            ->offset($offset * ($page - 1))
            ->limit($offset)
            ->all();

        return $this->asJson($users);
    }
}
