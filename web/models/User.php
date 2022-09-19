<?php

namespace app\models;

class User extends \yii\db\ActiveRecord
{
    public static function tableName()
    {
        return 'users';
    }


    public function rules()
    {
        return [
            [['login', 'email', 'password', 'retypePassword'], 'trim'],
            [['login', 'email', 'password', 'retypePassword'], 'required'],
            ['email', 'email'],
        ];
    }
}