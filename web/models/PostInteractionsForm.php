<?php

namespace app\models;

use yii\db\ActiveRecord;
use Yii;
use yii\db\Exception;

class PostInteractionsForm extends ActiveRecord
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
     * Проверяет, редактировал ли пользователь выбранный пост.
     * @param int $postId ID поста.
     * @return array|bool Найденный пост во временном хранилище постов|false, если пост редактируется первый раз.
     * @throws Exception
     */
    public function checkIsUpdate(int $postId): array|bool
    {
        return Yii::$app
            ->getDb()
            ->createCommand('SELECT * FROM ' . $this->_postsTmp . ' WHERE update_id = ' . $postId)
            ->queryOne();
    }

    /**
     * Подготавливает пост к публикации/обновлению или занесению во временное хранилище постов.
     * @param int|null $updateId ID обновляемого поста.
     * @return array Параметры для составления SQL запроса к БД.
     */
    private function preparePost(int|null $updateId = null): array
    {
        $params = [
            'title' => $this->title,
            'body' => $this->body,
            'author' => Yii::$app->session['login'],
        ];

        if (Yii::$app->session->has('admin')) {
            $table = self::tableName();
        } else {
            if (Yii::$app->requestedRoute == 'posts/edit-post') {
                $params['isNew'] = false;
                $params['update_id'] = $updateId;
            }
            $table = $this->_postsTmp;
        }
        return ['table' => $table, 'params' => $params];
    }

    /**
     * Создает пост в соответствующей таблице в БД.
     * @param int|null $postId ID поста, указывается, если редактируется старый пост.
     * @return void
     * @throws Exception
     */
    public function createPost(int|null $postId = null): void
    {
        $data = $this->preparePost($postId);
        Yii::$app
            ->getDb()
            ->createCommand()
            ->insert($data['table'], $data['params'])
            ->execute();
    }

    /**
     * Обновляет запись в таблице с постами.
     * @param int $id ID поста.
     * @return void
     * @throws Exception
     */
    public function updatePost(int $id): void
    {
        $data = $this->preparePost(null);
        Yii::$app
            ->getDb()
            ->createCommand()
            ->update($data['table'], $data['params'], 'id = ' . $id)
            ->execute();
    }
}
