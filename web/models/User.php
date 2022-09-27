<?php

declare(strict_types = 1);

namespace app\models;

use app\models\queries\UserQuery;
use yii\db\ActiveRecord;

class User extends ActiveRecord
{
    /**
     * {@inheritDoc}
     */
    public static function tableName(): string
    {
        return 'users';
    }

    /**
     * {@inheritDoc}
     */
    public static function find(): UserQuery
    {
        return new UserQuery(self::class);
    }

    /**
     * @return string Логин пользователя.
     */
    public function getLogin(): string
    {
        return $this->getAttribute('login');
    }

    /**
     * @return string Email пользователя.
     */
    public function getEmail(): string
    {
        return $this->getAttribute('email');
    }

    /**
     * @return bool Является ил пользователь админом.
     */
    public function getIsAdmin(): bool
    {
        return $this->getAttribute('isAdmin');
    }
}
