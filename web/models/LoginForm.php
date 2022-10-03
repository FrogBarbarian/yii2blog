<?php

declare(strict_types = 1);

namespace app\models;

use yii\db\ActiveRecord;
use yii\db\Exception;
use Yii;

class LoginForm extends ActiveRecord
{
    /**
     * @var string Email, введенный в форму логина.
     */
    public string $email = '';
    /**
     * @var string Пароль, введенный в форму логина.
     */
    public string $password = '';

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
        $user = Yii::$app
            ->getDb()
            ->createCommand('SELECT * FROM ' . self::tableName() . ' WHERE email = \'' . $this->email . '\'')
            ->queryOne();
        if (!$user || !password_verify($this->password, $user['password'])) {
            $this->addError($attribute, "Почта или пароль введены не верно");
        }
    }
}
