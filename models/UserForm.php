<?php

declare(strict_types = 1);

namespace app\models;

use Yii;
use yii\db\ActiveRecord;
use yii\db\Exception;

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
    const SCENARIO_CHANGE_EMAIL = 'change email';

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
            ['email' , 'email', 'message' => 'Введенный email не корректный'],
            ['email', 'uniqueCaseInsensitiveValidation'],
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
     * Проверяет на регистронезависимую уникальность введенные email/username при регистрации.
     * @param string $attribute Проверяемый атрибут.
     * @return void
     * @throws Exception
     */
    public function uniqueCaseInsensitiveValidation(string $attribute): void
    {
        if (
            Yii::$app->getDb()->createCommand(
            "SELECT id FROM " . self::tableName() . " WHERE $attribute ILIKE '{$this->attributes[$attribute]}'"
            )
            ->queryOne()
        ) {
            $error = match ($attribute) {
                'username' => 'Данное имя пользователя уже занято',
                default => 'Данный email уже занят',
            };
            $this->addError($attribute, $error);
        }
    }
}
