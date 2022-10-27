<?php

declare(strict_types = 1);

namespace app\models\queries;

use yii\db\ActiveQuery;

class UserQuery extends ActiveQuery
{
    /**
     * Поиск по ID пользователя.
     */
    public function byId(int $id): self
    {
        return $this->where(['id' => $id]);
    }

    /**
     * Поиск по имени пользователя.
     */
    public function byUsername(string $username): self
    {
        return $this->where(['ILIKE', 'username', $username]);
    }

    /**
     * Поиск по email пользователя.
     */
    public function byEmail(string $email): self
    {
        return $this->where(['email' => $email]);
    }

    /**
     * Сортирует по ID в прямом порядке.
     */
    public function orderAscById(): self
    {
        return $this->orderBy(['id' => SORT_ASC]);
    }
}
