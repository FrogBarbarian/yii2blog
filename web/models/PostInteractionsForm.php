<?php

declare(strict_types = 1);

namespace app\models;

use src\services\StringService;
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
                'length' => [30, 150],
                'tooShort' => 'Название не может быть короче 30 символов (сейчас - ' . $this->fieldLength('title') . ')',
                'tooLong' => 'Название не может быть длиннее 150 символов (сейчас - ' . $this->fieldLength('title') . ')',
            ],
            ['body', 'required', 'message' => 'Заполните содержимое поста'],
            [
                'body',
                'string',
                'length' => [300, 10000],
                'tooShort' => 'Содержание не может быть короче 300 символов (сейчас - ' . $this->fieldLength('body') . ')',
                'tooLong' => 'Содержание не может быть длиннее 10000 символов (сейчас - ' . $this->fieldLength('body') . ')',
            ],
        ];
    }

    /**
     * Получает длину строки выбранного поля.
     * @param string $field Поле.
     * @return int
     */
    private function fieldLength(string $field): int
    {
        $attribute = $_POST['PostInteractionsForm'][$field] ?? '';

        return (new StringService($attribute))
        ->getLength();
    }
}
