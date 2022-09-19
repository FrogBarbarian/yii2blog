<?php

namespace app\models;

use yii\db\ActiveRecord;

class RegistryForm extends ActiveRecord
{
    public string $login = '';
    public string $email = '';
    public string $password = '';
    public string $retypePassword = '';

    public function rules(): array
    {
        return [
            [['login', 'email', 'password', 'retypePassword'], 'trim'],
            ['login', 'required', 'message' => 'Придумайте логин'],
            ['login', 'string', 'length' => [3, 20], 'tooLong' => 'Максимум 20 символов', 'tooShort' => 'Минимум 3 символа'],
            ['login', 'match', 'pattern' => '/^[a-z]\w*$/i', 'message' => 'Используются недопустимые символы'],
            ['login', 'uniqueCaseInsensitiveValidation'],
            ['email', 'required', 'message' => 'Введите Ваш email'],
            ['email' , 'email', 'message' => 'Введенный email не корректный'],
            ['email', 'uniqueCaseInsensitiveValidation'],
            ['password', 'required', 'message' => 'Придумайте пароль'],
            ['password', 'string', 'length' => [5, 30], 'tooLong' => 'Максимум 30 символов', 'tooShort' => 'Минимум 5 символов'],
            ['password', 'match', 'pattern' => '/^[\w-]+$/i', 'message' => 'Используются недопустимые символы'],
            ['retypePassword', 'required', 'message' => 'Подтвердите Ваш пароль'],
            ['retypePassword', 'compare', 'compareAttribute' => 'password', 'message' => 'Пароли не совпадают'],
        ];
    }

    public static function tableName(): string
    {
        return 'users';
    }

    public function uniqueCaseInsensitiveValidation($attribute, $params)
    {
        if (\Yii::$app->getDb()->createCommand(
            "SELECT id FROM users WHERE $attribute ILIKE '{$this->attributes[$attribute]}'")
            ->queryOne()
        ) {
            switch ($attribute) {
                case 'login': $field = 'логин'; break;
                default: $field = 'email'; break;
            }
            $this->addError($attribute, "Данный $field уже занят");
        }
    }

    public function getRegisterData(): array
    {
        return ['login' => $this->login, 'email' => $this->email, 'password' => $this->password];
    }
}