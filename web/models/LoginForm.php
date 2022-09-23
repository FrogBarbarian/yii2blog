<?php

namespace app\models;

use yii\db\ActiveRecord;
use Yii;
use yii\db\Exception;

class LoginForm extends ActiveRecord
{
    public string $email = '';
    public string $password = '';

    /**
     * @return array Правила валидации регистрации нового юзера.
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
        if (!password_verify($this->password, $user['password'])) {
            $this->addError($attribute, "Почта или пароль введены не верно");
        }
    }
}