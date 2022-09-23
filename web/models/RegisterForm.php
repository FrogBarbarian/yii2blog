<?php

namespace app\models;

use yii\db\ActiveRecord;

class RegisterForm extends ActiveRecord
{
    public string $login = '';
    public string $email = '';
    public string $password = '';
    public string $confirmPassword = '';

    public function rules(): array
    {
        return [
            [['login', 'email', 'password'], 'trim'],
            ['login', 'required', 'message' => 'Придумайте псевдоним'],
            ['login', 'string', 'length' => [3, 20], 'tooLong' => 'Максимум 20 символов', 'tooShort' => 'Минимум 3 символа'],
            ['login', 'match', 'pattern' => '/^[a-z]\w*$/i', 'message' => 'Используются недопустимые символы'],
            ['email', 'required', 'message' => 'Введите Ваш email'],
            ['email' , 'email', 'message' => 'Введенный email не корректный'],
            ['password', 'required', 'message' => 'Придумайте пароль'],
            ['password', 'string', 'length' => [5, 30], 'tooLong' => 'Максимум 30 символов', 'tooShort' => 'Минимум 5 символов'],
            ['password', 'match', 'pattern' => '/^[\w-]+$/i', 'message' => 'Используются недопустимые символы'],
            ['confirmPassword', 'required', 'message' => 'Подтвердите Ваш пароль'],
            ['confirmPassword', 'compare', 'compareAttribute' => 'password', 'message' => 'Пароли не совпадают'],
        ];
    }

    public static function tableName(): string
    {
        return 'users';
    }
}