<?php

namespace app\models;

use yii\db\ActiveRecord;
use yii\db\Exception;
use Yii;

class Post extends ActiveRecord
{
    /**
     * @var string Таблица с постами пользователей.
     */
    private string $_posts = 'posts';
    /**
     * Получает случайный пост.
     * @return array|bool Результат выборки|false.
     * @throws Exception
     */
    public function getRandomPost(): array|bool
    {
        return Yii::$app
            ->getDb()
            ->createCommand('SELECT * FROM ' . $this->_posts . ' ORDER BY random() LIMIT 1')
            ->queryOne();
    }

    /**
     * Возвращает данные поста по искомому ID.
     * @param int $id Искомый id.
     * @return array|bool Результат выборки|false.
     * @throws Exception
     */
    public function getPostById(int $id): array|bool
    {
        return Yii::$app
            ->getDb()
            ->createCommand('SELECT * FROM ' . $this->_posts . ' WHERE id = ' . $id)
            ->queryOne();
    }

    /**
     * Увеличивает количество просмотров у поста с указанным ID.
     * @param string $id ID поста.
     * @return void
     * @throws Exception
     */
    public function increasePostViews(string $id): void
    {
        Yii::$app
            ->getDB()
            ->createCommand('UPDATE ' . $this->_posts . ' SET viewed = viewed + 1 WHERE id = ' . $id )
            ->execute();
    }
}
