<?php

namespace app\models;

use app\interfaces\UserData;
use yii\db\ActiveRecord;
use Yii;
use yii\db\Exception;

class Admin extends ActiveRecord implements UserData
{
    /**
     * @var string Таблица с временным хранением постов.
     */
    private string $_postsTmp = 'posts_tmp';
    /**
     * @var string Таблица с пользователями.
     */
    private string $_users = 'users';

    /**
     * Получает все посты из временного хранилища.
     * @return array
     * @throws Exception
     */
    public function getUsersTmpPosts(): array
    {
        return Yii::$app
            ->getDb()
            ->createCommand('SELECT * FROM ' . $this->_postsTmp)
            ->queryAll();
    }

    /**
     * Получает пост по ID из GET параметра.
     * @return array
     * @throws Exception
     */
    public function getUserTmpPost(): array
    {
        return Yii::$app
            ->getDb()
            ->createCommand('SELECT * FROM ' . $this->_postsTmp . ' WHERE id = ' . $_GET['id'])
            ->queryOne();
    }

    /**
     * Возвращает данные пользователя по ID из GET параметра.
     * @return array|bool Результат выборки|false.
     * @throws Exception
     */
    public function getUser(): array|bool
    {
        return Yii::$app
            ->getDb()
            ->createCommand('SELECT * FROM ' . $this->_users . ' WHERE id = ' . $_GET['id'])
            ->queryOne();
    }
}