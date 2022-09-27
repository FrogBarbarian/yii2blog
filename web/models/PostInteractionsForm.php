<?php

declare(strict_types = 1);

namespace app\models;

use yii\db\ActiveRecord;

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
}