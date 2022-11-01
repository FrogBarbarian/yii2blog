<?php

declare(strict_types = 1);

namespace app\models\queries;

use yii\db\ActiveQuery;

class ComplaintQuery extends ActiveQuery
{
    /**
     * Поиск по ID отправителя.
     */
    public function bySenderId(int $id): self
    {
        return $this->where(['sender_id' => $id]);
    }

    /**
     * Поиск по ID.
     */
    public function byId(int $id): self
    {
        return $this->where(['id' => $id]);
    }

    /**
     * Поиск по совпадению в колонках:
     * имя пользователя отправителя, тип объекта, id объекта.
     */
    public function same($senderUsername, $object, $objectId): self
    {
        return $this
            ->where(['sender_username' => $senderUsername])
            ->andWhere(['object' => $object])
            ->andWhere(['object_id' => $objectId]);
    }
}
