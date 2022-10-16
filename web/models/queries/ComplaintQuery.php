<?php

declare(strict_types = 1);

namespace app\models\queries;

use yii\db\ActiveQuery;

class ComplaintQuery extends ActiveQuery
{
    /**
     * Поиск по ID отправителя жалобы.
     */
    public function bySenderId(int $id): self
    {
        return $this->where(['sender_id' => $id]);
    }
}