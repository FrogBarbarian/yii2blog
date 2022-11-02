<?php

declare(strict_types = 1);

namespace app\models;

use app\models\queries\UserQuery;
use Yii;
use yii\base\Exception;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;

class User extends ActiveRecord implements IdentityInterface
{
    const STATUS_DELETED = 0;
    const STATUS_ACTIVE = 10;

    /**
     * {@inheritDoc}
     * @throws Exception
     */
    public function beforeSave($insert): bool
    {
        if (parent::beforeSave($insert)) {
            if ($this->isNewRecord) {
                $this->setAuthKey(Yii::$app->security->generateRandomString());
            }

            return true;
        }

        return false;
    }

    /**
     * {@inheritDoc}
     */
    public static function tableName(): string
    {
        return 'users';
    }

    /**
     * @inheritdoc
     */
    public function behaviors(): array
    {
        return [
            TimestampBehavior::class,
        ];
    }

    /**
     * {@inheritDoc}
     */
    public static function findIdentity($id): ?self
    {
        return static::findOne(['id' => $id, 'status' => self::STATUS_ACTIVE]);
    }

    /**
     * {@inheritDoc}
     */
    public static function findIdentityByAccessToken($token, $type = null): self
    {
        return static::findOne(['access_token' => $token]);
    }

    /**
     * @return string Ключ аутентификации.
     */
    public function getAuthKey(): string
    {
        return $this->getAttribute('auth_key');
    }

    /**
     * Записывает ключ аутентификации.
     */
    private function setAuthKey(string $authKey)
    {
        $this->setAttribute('auth_key', $authKey);
    }

    /**
     * Проверяет совпадение ключа аутентификации.
     */
    public function validateAuthKey($authKey): bool
    {
        return $this->getAuthKey() === $authKey;
    }

    /**
     * {@inheritDoc}
     */
    public function rules():array
    {
        return [
            ['status', 'default', 'value' => self::STATUS_ACTIVE],
            ['status', 'in', 'range' => [self::STATUS_ACTIVE, self::STATUS_DELETED]],
        ];
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
     * @return string Имя пользователя.
     */
    public function getUsername(): string
    {
        return $this->getAttribute('username');
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
        return $this->getAttribute('is_admin');
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
     * @return bool Есть ли право писать ЛС.
     */
    public function getCanWriteMessages(): bool
    {
        return $this->getAttribute('can_write_messages');
    }

    /**
     * @return bool Есть ли право писать посты.
     */
    public function getCanWritePosts(): bool
    {
        return $this->getAttribute('can_write_posts');
    }

    /**
     * Записывает имя пользователя.
     * @param string $username
     * @return self
     */
    public function setUsername(string $username): self
    {
        $this->setAttribute('username', $username);

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
     * @throws Exception
     */
    public function setPassword(string $password): self
    {
        $this->setAttribute('password_hash', Yii::$app->security->generatePasswordHash($password));

        return $this;
    }

    /**
     * @return string Хеш пароля.
     */
    private function getPasswordHash(): string
    {
        return $this->getAttribute('password_hash');
    }

    /**
     * Проверяет на валидность введенный пароль.
     */
    public function validatePassword(string $password): bool
    {
        return Yii::$app->security->validatePassword($password, $this->getPasswordHash());
    }

    /**
     * Генерирует аутентификационный ключ.
     * @throws Exception
     */
    public function generateAuthKey(): self
    {
        $this->setAuthKey(Yii::$app->security->generateRandomString());

        return $this;
    }

    /**
     * Принимает значение, является ли пользователь админом.
     * @param bool $isAdmin
     * @return self
     */
    public function setIsAdmin(bool $isAdmin): self
    {
        $this->setAttribute('is_admin', $isAdmin);

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

    /**
     * Устанавливает, может ли пользователь комментировать посты.
     */
    public function setCanComment(bool $canComment): self
    {
        $this->setAttribute('can_comment', $canComment);

        return $this;
    }

    /**
     * Устанавливает, может ли пользователь писать ЛС.
     */
    public function setCanWriteMessages(bool $canWriteMessages): self
    {
        $this->setAttribute('can_write_messages', $canWriteMessages);

        return $this;
    }

    /**
     * Устанавливает, может ли пользователь писать посты.
     */
    public function setCanWritePosts(bool $canWritePosts): self
    {
        $this->setAttribute('can_write_posts', $canWritePosts);

        return $this;
    }

    /**
     * Забанен ли пользователь.
     */
    public function setIsBanned(bool $isBanned): self
    {
        $this->setAttribute('is_banned', $isBanned);

        return $this;
    }

    /**
     * @return bool Забнен ли пользователь.
     */
    public function getIsBanned(): bool
    {
        return $this->getAttribute('is_banned');
    }

    /**
     * Открыты ли личные сообщения.
     */
    public function setIsMessagesOpen(bool $isOpen): self
    {
        $this->setAttribute('is_pm_open', $isOpen);

        return $this;
    }

    /**
     * @return bool Открыты ли личные сообщения.
     */
    public function getIsMessagesOpen(): bool
    {
        return $this->getAttribute('is_pm_open');
    }
}
