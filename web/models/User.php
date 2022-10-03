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
     * @return int ID пользователя.
     */
    public function getId(): int
    {
        return $this->getAttribute('id');
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

    /**
     * @return bool Скрыт ли профиль пользователя.
     */
    public function getIsHidden(): bool
    {
        return $this->getAttribute('is_hidden');
    }

    /**
     * @return bool Есть ли право комментировать.
     */
    public function getCanComment(): bool
    {
        return $this->getAttribute('can_comment');
    }

    /**
     * Записывает псевдоним пользователя.
     * @param string $login
     * @return self
     */
    public function setLogin(string $login): self
    {
        $this->setAttribute('login', $login);

        return $this;
    }

    /**
     * Записывает email пользователя.
     * @param string $email
     * @return self
     */
    public function setEmail(string $email): self
    {
        $this->setAttribute('email', $email);

        return $this;
    }

    /**
     * Записывает пароль пользователя.
     * @param string $password
     * @return self
     */
    public function setPassword(string $password): self
    {
        $this->setAttribute('password', password_hash($password, PASSWORD_DEFAULT));

        return $this;
    }

    /**
     * Принимает значение, является ли пользователь админом.
     * @param bool $isAdmin
     * @return self
     */
    public function setIsAdmin(bool $isAdmin): self
    {
        $this->setAttribute('isAdmin', $isAdmin);

        return $this;
    }

    /**
     * Скрывает/показывает профиль пользователя.
     * @param bool $isHidden
     * @return self
     */
    public function setIsHidden(bool $isHidden): self
    {
        $this->setAttribute('is_hidden', $isHidden);

        return $this;
    }
}
