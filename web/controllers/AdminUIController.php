<?php

declare(strict_types=1);

namespace app\controllers;

use app\models\Complaint;
use app\models\Tag;
use Psr\SimpleCache\InvalidArgumentException;
use src\helpers\Get;
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
     * TODO: WHAT
     * @throws NotFoundHttpException
     * @throws InvalidArgumentException
     */
    public function actionGetObject(string $object, string $param, string $type, string $useCache): Response
    {
        $useCache = $useCache === 'true';

        if (!Yii::$app->request->isAjax) {
            throw new NotFoundHttpException();
        }

        $type = (int)$type;
        $tags = Get::data($object, $param, $type, $useCache);

        return $this->asJson($tags);
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
