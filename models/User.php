<?php

declare(strict_types = 1);

namespace app\models;

use app\models\queries\UserQuery;
use Yii;
use yii\base\Exception;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;

/**
 * Модель пользователя.
 */
class User extends ActiveRecord implements IdentityInterface
{
    /**
     * Статус: удален.
     */
    const STATUS_DELETED = 0;
    /**
     * Статус: активен.
     */
    const STATUS_ACTIVE = 10;
    /**
     * Статус: забанен.
     */
    const STATUS_BANNED = 20;

    /**
     * {@inheritDoc}
     *
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
     * Ключ аутентификации.
     */
    private function setAuthKey(string $authKey)
    {
        $this->setAttribute('auth_key', $authKey);
    }

    /**
     * Генерирует аутентификационный ключ.
     *
     * @throws Exception
     */
    public function generateAuthKey(): self
    {
        $this->setAuthKey(Yii::$app->security->generateRandomString());

        return $this;
    }

    /**
     * @return string Ключ аутентификации.
     */
    public function getAuthKey(): string
    {
        return $this->getAttribute('auth_key');
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
            ['status', 'in', 'range' => [self::STATUS_ACTIVE, self::STATUS_DELETED, self::STATUS_BANNED]],
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
     * Статус.
     */
    public function setStatus(int $status): self
    {
        $this->setAttribute('status', $status);

        return $this;
    }

    /**
     * @return int Статус.
     */
    public function getStatus(): int
    {
        return $this->getAttribute('status');
    }

    /**
     * Имя.
     */
    public function setUsername(string $username): self
    {
        $this->setAttribute('username', $username);

        return $this;
    }

    /**
     * @return string Имя.
     */
    public function getUsername(): string
    {
        return $this->getAttribute('username');
    }

    /**
     * Почта.
     */
    public function setEmail(string $email): self
    {
        $this->setAttribute('email', $email);

        return $this;
    }

    /**
     * @return string Почта.
     */
    public function getEmail(): string
    {
        return $this->getAttribute('email');
    }

    /**
     * Является ли пользователь админом.
     */
    public function setIsAdmin(bool $isAdmin): self
    {
        $this->setAttribute('is_admin', $isAdmin);

        return $this;
    }

    /**
     * @return bool Является ил пользователь админом.
     */
    public function getIsAdmin(): bool
    {
        return $this->getAttribute('is_admin');
    }

    /**
     * Скрыт ли профиль пользователя.
     */
    public function setIsHidden(bool $isHidden): self
    {
        $this->setAttribute('is_hidden', $isHidden);

        return $this;
    }

    /**
     * @return bool Скрыт ли профиль пользователя.
     */
    public function getIsHidden(): bool
    {
        return $this->getAttribute('is_hidden');
    }

    /**
     * Есть ли право комментировать.
     */
    public function setCanComment(bool $canComment): self
    {
        $this->setAttribute('can_comment', $canComment);

        return $this;
    }

    /**
     * @return bool Есть ли право комментировать.
     */
    public function getCanComment(): bool
    {
        return $this->getAttribute('can_comment');
    }

    /**
     * Есть ли право писать ЛС.
     */
    public function setCanWriteMessages(bool $canWriteMessages): self
    {
        $this->setAttribute('can_write_messages', $canWriteMessages);

        return $this;
    }

    /**
     * @return bool Есть ли право писать ЛС.
     */
    public function getCanWriteMessages(): bool
    {
        return $this->getAttribute('can_write_messages');
    }

    /**
     * Есть ли право писать посты.
     */
    public function setCanWritePosts(bool $canWritePosts): self
    {
        $this->setAttribute('can_write_posts', $canWritePosts);

        return $this;
    }

    /**
     * @return bool Есть ли право писать посты.
     */
    public function getCanWritePosts(): bool
    {
        return $this->getAttribute('can_write_posts');
    }

    /**
     * Записывает пароль пользователя.
     *
     * @throws Exception
     */
    public function setPassword(string $password): self
    {
        $this->setAttribute('password_hash',
            Yii::$app
                ->security
                ->generatePasswordHash($password));


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
        return Yii::$app
            ->security
            ->validatePassword($password, $this->getPasswordHash());
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
     * @return bool Забанен ли пользователь.
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

    /**
     * Находит пользователя по токену восстановления пароля.
     */
    public static function findByPasswordResetToken(string $token): ?static
    {

        if (!static::isPasswordResetTokenValid($token)) {
            return null;
        }

        return static::findOne([
            'password_reset_token' => $token,
            'status' => self::STATUS_ACTIVE,
        ]);
    }

    /**
     * Проверяет, истек ли срок хранения токена для восстановления пароля.
     */
    public static function isPasswordResetTokenValid(string $token): bool
    {
        if (empty($token)) {
            return false;
        }

        $timestamp = (int)substr($token, strrpos($token, '_') + 1);
        $expire = Yii::$app->params['passwordResetTokenExpire'];

        return $timestamp + $expire >= time();
    }

    /**
     * Генерирует токен восстановления пароля.
     *
     * @throws Exception
     */
    public function generatePasswordResetToken(): self
    {
        $value = Yii::$app
                ->security
                ->generateRandomString() .
            '_' . time();
        $this->setAttribute('password_reset_token', $value);

        return $this;
    }

    /**
     * Сбрасывает токен восстановления пароля.
     */
    public function removePasswordResetToken(): self
    {
        $this->setAttribute('password_reset_token', null);

        return $this;
    }

    /**
     * @return string Токен восстановления пароля.
     */
    public function getPasswordResetToken(): string
    {
        return $this->getAttribute('password_reset_token');
    }
}
