<?php

declare(strict_types = 1);

namespace app\models\queries;

use yii\db\ActiveQuery;

/**
 * Запросы к жалобам.
 */
class ComplaintQuery extends ActiveQuery
{
    /**
     * Поиск по ID отправителя.
     */
    public function bySenderUsername(string $username): self
    {
        return $this->where(['sender_username' => $username]);
    }
}
