<?php

namespace app\models;

use yii\db\ActiveRecord;

class Investments extends ActiveRecord
{
    public static function tableName(): string
    {
        return 'bills';
    }

    public function addBill(array $attributes): void
    {
        $params = ['author' => \Yii::$app->session['login'], 'title' => $attributes['title'], 'amount' => $attributes['amount'], 'term' => $attributes['term']];
        \Yii::$app->getDB()->createCommand()->insert('bills', $params)->execute();
    }

    public function getBills()
    {
        return \Yii::$app->getDb()
            ->createCommand('SELECT * FROM bills WHERE author = \'' . \Yii::$app->session['login'] . '\' ORDER BY id ASC')
            ->queryAll();
    }

    public function deleteBill(string $id): void
    {
        \Yii::$app->getDB()->createCommand()->delete('bills', 'id = ' . $id)->execute();
    }

    public function editBill(string $id, array $params): void
    {
        $params = array_diff($params, array(''));
        \Yii::$app->getDB()->createCommand()->update('bills', $params, 'id = ' . $id)->execute();
    }
}