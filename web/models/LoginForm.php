<?php

namespace app\models;

use yii\db\ActiveRecord;

class LoginForm extends ActiveRecord
{
    public string $login = '';
    public string $password = '';
    public bool $isRemember = false;

    public static function tableName(): string
    {
        return 'users';
    }

    public function rules(): array
    {
        return [
            [['login', 'email', 'password'], 'trim'],
            ['login', 'required', 'message' => 'Введите логин'],
            ['password', 'required', 'message' => 'Введите пароль'],
        ];
    }
}