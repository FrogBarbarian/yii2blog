<?php

declare(strict_types = 1);

namespace app\models\queries;

use yii\db\ActiveQuery;

class StatisticsQuery extends ActiveQuery
{
    /**
     * Поиск по имени пользователя.
     */
    public function byUsername(string $username): self
    {
        return $this->where(['owner' => $username]);
    }
}