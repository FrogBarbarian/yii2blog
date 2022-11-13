<?php

declare(strict_types = 1);

namespace app\models;

use yii\db\ActiveRecord;

/**
 * Форма входа.
 */
class LoginForm extends ActiveRecord
{
    /**
     * @var string Почта.
     */
    public string $email = '';
    /**
     * @var string Пароль.
     */
    public string $password = '';
    /**
     * @var bool Чекбокс 'запомнить меня'.
     */
    public bool $rememberMe = true;

    /**
     * {@inheritDoc}
     */
    public function rules(): array
    {
        return [
            [['email', 'password'], 'trim'],
            ['email', 'required', 'message' => 'Заполните поле почта'],
            ['email' , 'email', 'message' => 'Введенный email не корректный'],
            ['password', 'required', 'message' => 'Заполните поле пароль'],
            ['password', 'checkData'],
            ['rememberMe', 'boolean'],
            ['email', 'checkUserBan'],
        ];
    }

    /**
     * {@inheritDoc}
     */
    public static function tableName(): string
    {
        return 'users';
    }

    /**
     * Проверяет на корректность введенные данные для входа.
     */
    public function checkData(string $attribute): void
    {
        $user = User::find()
            ->byEmail($this->email)
            ->one();

        if (!$user || !$user->validatePassword($this->password)) {
            $this->addError($attribute, "Почта или пароль введены не верно");
        }
    }

    /**
     * Проверяет на наличие бана у пользователя.
     */
    public function checkUserBan(string $attribute): void
    {
        $user = User::find()
            ->byEmail($this->email)
            ->one();

        if ($user !== null && $user->getIsBanned()) {
            $this->addError($attribute, "Ваша учетная запись заблокирована");
        }
    }
}
