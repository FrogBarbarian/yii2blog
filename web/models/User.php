<?php

namespace app\models;

class User extends \yii\db\ActiveRecord
{
    public function registerUser(array $params): void
    {
        \Yii::$app->getDB()->createCommand()->insert('users', $params)->execute();
    }

    public static function tableName(): string
    {
        return 'users';
    }
}