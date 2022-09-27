<?php

declare(strict_types = 1);

namespace app\models\queries;

use yii\db\ActiveQuery;

class UserQuery extends ActiveQuery
{
    /**
     * Поиск по псевдониму пользователя.
     */
    public function byLogin(string $login): self
    {
        return $this->where(['login' => $login]);
    }

    /**
     * Поиск по email пользователя.
     */
    public function byEmail(string $email): self
    {
        return $this->where(['email' => $email]);
    }
}
