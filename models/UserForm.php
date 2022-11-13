<?php

declare(strict_types=1);

namespace app\models;

use yii\db\ActiveRecord;

/**
 * Форма пользователя.
 */
class UserForm extends ActiveRecord
{
    /**
     * @var string Имя пользователя.
     */
    public string $username = '';
    /**
     * @var string Email.
     */
    public string $email = '';
    /**
     * @var string Пароль.
     */
    public string $password = '';
    /**
     * @var string Подтверждение пароля.
     */
    public string $confirmPassword = '';
    /**
     * Сценарий: смена почты.
     */
    const SCENARIO_CHANGE_EMAIL = 'change email';
    /**
     * Сценарий: сброс пароля.
     */
    const SCENARIO_RESTORE_PASSWORD = 'restore password';
    /**
     * Сценарий: новый пароль.
     */
    const SCENARIO_NEW_PASSWORD = 'new password';

    /**
     * {@inheritDoc}
     */
    public function rules(): array
    {
        return [
            [['username', 'email', 'password', 'confirmPassword'], 'trim'],
            ['username', 'required', 'message' => 'Придумайте имя пользователя'],
            ['username', 'string', 'length' => [3, 30], 'tooLong' => 'Максимум 30 символов', 'tooShort' => 'Минимум 3 символа'],
            ['username', 'match', 'pattern' => '/^[\da-zА-яёЁ][\wА-яёЁ]+/i', 'message' => 'Используются недопустимые символы'],
            ['username', 'uniqueCaseInsensitiveValidation'],
            ['email', 'required', 'message' => 'Введите Ваш email'],
            ['email', 'email', 'message' => 'Введенный email не корректный'],
            ['email', 'uniqueCaseInsensitiveValidation', 'on' => [self::SCENARIO_CHANGE_EMAIL, self::SCENARIO_DEFAULT]],
            ['email', 'checkEmailExist', 'on' => self::SCENARIO_RESTORE_PASSWORD],
            ['password', 'required', 'message' => 'Придумайте пароль'],
            ['password', 'string', 'length' => [5, 30], 'tooLong' => 'Максимум 30 символов', 'tooShort' => 'Минимум 5 символов'],
            ['password', 'match', 'pattern' => '/^[\w-]+$/i', 'message' => 'Используются недопустимые символы'],
            ['confirmPassword', 'required', 'message' => 'Подтвердите Ваш пароль'],
            ['confirmPassword', 'compare', 'compareAttribute' => 'password', 'message' => 'Пароли не совпадают'],
        ];
    }

    /**
     * {@inheritDoc}
     */
    public function scenarios(): array
    {
        $scenarios = parent::scenarios();
        $scenarios[self::SCENARIO_CHANGE_EMAIL] = ['email'];
        $scenarios[self::SCENARIO_RESTORE_PASSWORD] = ['email'];
        $scenarios[self::SCENARIO_NEW_PASSWORD] = ['password'];

        return $scenarios;
    }

    /**
     * {@inheritDoc}
     */
    public static function tableName(): string
    {
        return 'users';
    }

    /**
     * Проверяет на регистронезависимую уникальность введенный email/username при регистрации.
     */
    public function uniqueCaseInsensitiveValidation(string $attribute): void
    {
        $user = User::find()
            ->where(['ILIKE', $attribute, "%{$this->$attribute}", false])
            ->one();

        if ($user !== null) {
            $error = match ($attribute) {
                'username' => 'Данное имя пользователя уже занято',
                'email' => 'Данная почта уже занята',
            };
            $this->addError($attribute, $error);
        }
    }

    /**
     * Проверка на существование пользователя с введенным Email.
     */
    public function checkEmailExist(string $attribute): void
    {
        $user = User::find()
            ->byEmail($this->email)
            ->one();

        if ($user === null) {
            $this->addError($attribute, 'Email не найден');
        }
    }
}
