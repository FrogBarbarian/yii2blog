<?php

declare(strict_types = 1);

namespace app\models;

use yii\db\ActiveRecord;
use yii\db\Exception;

class LoginForm extends ActiveRecord
{
    /**
     * @var string Email.
     */
    public string $email = '';
    /**
     * @var string Пароль.
     */
    public string $password = '';
    public bool $rememberMe = true;

    /**
     * @return array Правила валидации входа юзера.
     */
    public function rules(): array
    {
        return [
            [['email', 'password'], 'trim'],
            ['email', 'required', 'message' => 'Заполните поле почта'],
            ['email' , 'email', 'message' => 'Введенный email не корректный'],
            ['password', 'required', 'message' => 'Заполните поле пароль'],
            [['email', 'password'], 'checkData'],
            ['rememberMe', 'boolean'],
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
     * Проверяет на корректность введенные данные для входа.
     * @param string $attribute Проверяемый аттрибут.
     * @return void
     * @throws Exception
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
}
