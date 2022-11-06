<?php

declare(strict_types = 1);

namespace app\models;

use src\services\StringService;
use yii\db\ActiveRecord;

class PostEditorForm extends ActiveRecord
{
    /**
     * @var string Название.
     */
    public string $title = '';
    /**
     * @var string Текст.
     */
    public string $body = '';
    /**
     * @var string Теги.
     */
    public string $tags = '';
    /**
     * @var bool
     */
    public bool $isNew = true;

    /**
     * {@inheritDoc}
     */
    public static function tableName(): string
    {
        return 'posts';
    }

    /**
     * {@inheritDoc}
     */
    public function rules(): array
    {
        return [
            [['title', 'body'], 'trim'],
            ['title', 'required', 'message' => 'Придумайте название поста'],
            ['tags', 'required', 'message' => 'Выберете как минимум 1 тег'],
            ['title', 'checkNameIfPostIsNew'],
            [
                'title',
                'string',
                'length' => [30, 150],
                'tooShort' => 'Название не может быть короче 30 символов',
                'tooLong' => 'Название не может быть длиннее 150 символов',
            ],
            ['body', 'required', 'message' => 'Заполните содержимое поста'],
            [
                'body',
                'string',
                'length' => [300, 10000],
                'tooShort' => 'Содержание не может быть короче 300 символов',
                'tooLong' => 'Содержание не может быть длиннее 10000 символов',
            ],
        ];
    }

    /**
     * Проверка на уникальность имени, если пост новый.
     */
    public function checkNameIfPostIsNew()
    {
        if ($this->isNew) {
            $post = self::find()
                ->where(['ILIKE', 'title', $this->title])
                ->one();

            if ($post !== null) {
                $this->addError('title', 'Пост с таким именем уже существует');
            }
        }
    }
}
