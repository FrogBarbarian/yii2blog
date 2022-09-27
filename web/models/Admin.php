<?php

declare(strict_types = 1);

namespace app\models;

use yii\db\ActiveRecord;
use Yii;
use yii\db\Exception;

class Admin extends ActiveRecord
{
    /**
     * @var string ID из hidden input.
     */
    public string $id = '';
    /**
     * @var string Таблица с временным хранением постов.
     */
    private string $_postsTmp = 'posts_tmp';
    /**
     * @var string Таблица с постами.
     */
    private string $_posts = 'posts';
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
     * @param mixed $id ID поста.
     * @return array
     * @throws Exception
     */
    public function getUserTmpPost(mixed $id): array
    {
        return Yii::$app
            ->getDb()
            ->createCommand('SELECT * FROM ' . $this->_postsTmp . ' WHERE id = ' . $id)
            ->queryOne();
    }

    /**
     * Возвращает данные пользователя по ID из GET параметра.
     * @return array|bool Результат выборки|false.
     * @throws Exception
     */
    public function getUser(mixed $id): array|bool
    {
        return Yii::$app
            ->getDb()
            ->createCommand('SELECT * FROM ' . $this->_users . ' WHERE id = ' . $id)
            ->queryOne();
    }

    /**
     * Создает новый или изменяет старый пост в таблице постов, удаляет запись из хранилища постов.
     * @return void
     * @throws Exception
     */
    public function initPost(): void
    {
        $command = Yii::$app
            ->getDb()
            ->createCommand();
        $post = $this->getUserTmpPost($_POST['Admin']['id']);
        $params = [
            'title' => $post['title'],
            'body' => $post['body'],
            'author' => $post['author'],
        ];
        if ($_POST['Admin']['id']) {
            $command->insert($this->_posts, $params)->execute();
        } else {
            $command->update($this->_posts, $params)->execute();
        }

        $command
            ->delete($this->_postsTmp, 'id = ' . $post['id'])
            ->execute();
    }


}
