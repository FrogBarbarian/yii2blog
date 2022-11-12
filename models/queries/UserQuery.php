<?php

declare(strict_types = 1);

namespace app\models\queries;

use yii\db\ActiveQuery;

/**
 * Запросы к пользователям.
 */
class UserQuery extends ActiveQuery
{
    /**
     * Поиск по имени пользователя.
     */
    public function byUsername(string $username): self
    {
        return $this->where(['ILIKE', 'username', "%$username", false]);
    }

    /**
     * Поиск по символам в имени.
     */
    public function byChars(string $chars): self
    {
        return $this->where(['ILIKE', 'username', $chars]);
    }

    /**
     * Поиск по email пользователя.
     */
    public function byEmail(string $email): self
    {
        return $this->where(['email' => $email]);
    }
}
