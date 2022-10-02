<?php

namespace app\models\queries;

use yii\db\ActiveQuery;

class StatisticsQuery extends ActiveQuery
{
    /**
     * Поиск по логину пользователя.
     */
    public function byLogin(string $login): self
    {
        return $this->where(['owner' => $login]);
    }
}