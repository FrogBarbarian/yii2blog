<?php

declare(strict_types = 1);

namespace app\models;

use yii\db\ActiveRecord;
use Yii;
use yii\db\Exception;

class RegisterForm extends ActiveRecord
{
    /**
     * @var string Псевдоним, введенный в форму регистрации.
     */
    public string $login = '';
    /**
     * @var string Email, введенный в форму регистрации.
     */
    public string $email = '';
    /**
     * @var string Пароль, введенный в форму регистрации.
     */
    public string $password = '';
    /**
     * @var string Подтверждающий пароль, введенный в форму регистрации.
     */
    public string $confirmPassword = '';

    /**
     * @return array Правила валидации регистрации нового юзера.
     */
    public function rules(): array
    {
        return [
            //TODO: Актуализировать справку о правилах заполнения полей при регистрации
            [['login', 'email', 'password', 'confirmPassword'], 'trim'],
            ['login', 'required', 'message' => 'Придумайте псевдоним'],
            ['login', 'string', 'length' => [3, 20], 'tooLong' => 'Максимум 20 символов', 'tooShort' => 'Минимум 3 символа'],
            ['login', 'match', 'pattern' => '/[a-zА-яёЁ][\wА-яёЁ]+/i', 'message' => 'Используются недопустимые символы'],
            ['login', 'uniqueCaseInsensitiveValidation'],
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
     * @return string Название таблицы с пользователями.
     */
    public static function tableName(): string
    {
        return 'users';
    }

    /**
     * Проверяет на регистронезависимую уникальность введенные email/login при регистрации.
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
            $field = match ($attribute) {
                'login' => 'псевдоним',
                default => 'email',
            };
            $this->addError($attribute, "Данный $field уже занят");
        }
    }
}
