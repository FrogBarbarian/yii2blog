<?php

declare(strict_types = 1);

namespace app\models;

use src\helpers\NormalizeData;
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
            ['body', 'checkBodyTextLength'],
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

    /**
     * Проверяет длину строки атрибута 'body' без учета HTML тегов.
     */
    public function checkBodyTextLength() {
        $length = mb_strlen(strip_tags($this->body));

        if ($length === 0) {
            $message = 'Заполните содержимое поста';
            $this->addError('body', $message);
        }

        if ($length < 300) {
            $needChars = 300 - $length;
            $word =  NormalizeData::wordForm($needChars, 'символов', 'символ', 'символа');
            $format = 'Содержание должно быть длиннее, еще %d %s';
            $message = sprintf($format, $needChars, $word);
            $this->addError('body', $message);
        }

        if ($length > 10000) {
            $extraChars = $length - 10000;
            $word =  NormalizeData::wordForm($extraChars, 'символов', 'символ', 'символа');
            $format = 'Содержание должно быть короче, уберите %d %s';
            $message = sprintf($format, $extraChars, $word);
            $this->addError('body', $message);
        }
    }
}
