<?php

declare(strict_types=1);

namespace app\controllers;

use app\models\Tag;
use Psr\SimpleCache\InvalidArgumentException;
use src\helpers\Get;
use Yii;
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
     * Получает теги.
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
}
