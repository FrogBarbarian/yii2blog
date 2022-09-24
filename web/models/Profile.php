<?php

namespace app\models;

use app\interfaces\UserData;
use yii\db\ActiveRecord;
use Yii;
use yii\db\Exception;

class Profile extends ActiveRecord implements UserData
{
    /**
     * @var string Таблица с пользователями.
     */
    private string $users = 'users';
    /**
     * @var string Таблица с постами пользователей.
     */
    private string $posts = 'posts';

    /**
     * Возвращает данные пользователя по email из сессии.
     * @return array Результат выборки.
     * @throws Exception
     */
    public function getUser(): array
    {
        return Yii::$app
            ->getDb()
            ->createCommand('SELECT * FROM ' . $this->users . ' WHERE email = \'' . Yii::$app->session['login'] .  '\'')
            ->queryOne();
    }

    /**
     * Возвращает посты пользователя с указанным ID.
     * @param int $id ID пользователя.
     * @return array|bool Результат выборки|false.
     * @throws Exception
     */
    public function getUserPosts(int $id): array|bool
    {
        return Yii::$app
            ->getDb()
            ->createCommand('SELECT * FROM ' . $this->posts . ' WHERE author = ' . $id)
            ->queryAll();
    }

    /**
     * Возвращает данные всех пользователей.
     * @return array Результат выборки.
     * @throws Exception
     */
    public function getUsers(): array
    {
         $_users = Yii::$app
            ->getDb()
            ->createCommand('SELECT * FROM ' . $this->users)
            ->queryAll();
         foreach ($_users as $user) {
             $users[$user['id']] = $user;
         }
         return $users ?? [];
    }
}
