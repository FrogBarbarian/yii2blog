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
    private string $_users = 'users';
    /**
     * @var string Таблица с постами пользователей.
     */
    private string $_posts = 'posts';
    /**
     * @var string Таблица временного хранения с постами пользователей.
     */
    private string $_posts_tmp = 'posts_tmp';

    /**
     * Возвращает данные пользователя по email из сессии.
     * @return array|bool Результат выборки|false.
     * @throws Exception
     */
    public function getUser(): array|bool
    {
        return Yii::$app
            ->getDb()
            ->createCommand('SELECT * FROM ' . $this->_users . ' WHERE login = \'' . Yii::$app->session['login'] .  '\'')
            ->queryOne();
    }

    /**
     * Возвращает посты пользователя по логину.
     * @param string $login Логин пользователя.
     * @return array|bool Результат выборки|false.
     * @throws Exception
     */
    public function getUserPosts(string $login): array|bool
    {
        return Yii::$app
            ->getDb()
            ->createCommand('SELECT * FROM ' . $this->_posts . ' WHERE author = \'' . $login . '\'')
            ->queryAll();
    }

    /**
     * Возвращает посты пользователя из таблицы временного хранения постов по логину.
     * @param string $login Логин пользователя.
     * @return array|bool Результат выборки|false.
     * @throws Exception
     */
    public function getUserTmpPosts(string $login): array|bool
    {
        return Yii::$app
            ->getDb()
            ->createCommand('SELECT * FROM ' . $this->_posts_tmp . ' WHERE author = \'' . $login . '\'')
            ->queryAll();
    }
}
