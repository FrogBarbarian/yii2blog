<?php

namespace app\models;

use yii\db\ActiveRecord;
use Yii;
use yii\db\Exception;

class NewPostForm extends ActiveRecord
{
    /**
     * @var string Название нового поста.
     */
    public string $title = '';
    /**
     * @var string Текст нового поста.
     */
    public string $body = '';
    /**
     * @var string Таблица с временным хранением постов
     */
    private string $_postsTmp = 'posts_tmp';

    /**
     * @return string Название таблицы с постами.
     */
    public static function tableName(): string
    {
        return 'posts';
    }

    /**
     * @return array Правила валидации нового поста.
     */
    public function rules(): array
    {
        return [
            [['title', 'body'], 'trim'],
            ['title', 'required', 'message' => 'Придумайте название поста'],
            [
                'title',
                'string',
                'length' => [10, 100],
                'tooShort' => 'Название не может быть короче 10 символов',
                'tooLong' => 'Название не может быть длиннее 100 символов',
            ],
            ['body', 'required', 'message' => 'Заполните содержимое поста'],
            [
                'body',
                'string',
                'length' => [300, 10000],
                'tooShort' => 'Название не может быть короче 300 символов',
                'tooLong' => 'Название не может быть длиннее 10000 символов',
            ],
        ];
    }

    /**
     * Создает новый пост в таблице постов.
     * @return void
     * @throws Exception
     */
    public function createPost(): void
    {
        $table = isset(Yii::$app->session['admin']) ? self::tableName() : $this->_postsTmp;
        $params = [
            'title' => $this->title,
            'body' => $this->body,
            'author' => Yii::$app->session['id'],
        ];
        Yii::$app
            ->getDb()
            ->createCommand()
            ->insert($table, $params)
            ->execute();
    }

    /**
     * Возвращает данные последнего поста пользователя.
     * @return array|bool Результат выборки|false.
     * @throws Exception
     */
    public function getLastPost(): array|bool
    {
        return Yii::$app
            ->getDb()
            ->createCommand('SELECT * FROM ' . self::tableName() . ' WHERE author = ' . Yii::$app->session['id'] . ' ORDER BY id DESC')
            ->queryOne();
    }
}
