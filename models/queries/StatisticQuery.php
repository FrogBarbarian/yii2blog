<?php

declare(strict_types = 1);

namespace app\models\queries;

use yii\db\ActiveQuery;

/**
 * Запросы к статистике.
 */
class StatisticQuery extends ActiveQuery
{
    /**
     * Поиск по имени пользователя.
     */
    public function byUsername(string $username): self
    {
        return $this->where(['owner' => $username]);
    }

    /**
     * Фильтр по количеству лайков.
     * @param string $type Может принимать значения: <, >, <=, >=, =.
     */
    public function byLikes(int $amount = 0, string $type = '>'): self
    {
        return $this->where([$type, 'likes', $amount]);
    }

    /**
     * Фильтр по количеству дизлайков.
     * @param string $type Может принимать значения: <, >, <=, >=, =.
     */
    public function byDislikes(int $amount = 0, string $type = '>'): self
    {
        return $this->where([$type, 'dislikes', $amount]);
    }
}
