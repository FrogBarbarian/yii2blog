<?php

declare(strict_types = 1);

namespace app\models;

use src\services\StringService;
use yii\db\ActiveRecord;

/**
 * Форма комментария.
 */
class CommentForm extends ActiveRecord
{
    /**
     * @var string Содержание.
     */
    public string $comment = '';

    /**
     * {@inheritDoc}
     */
    public static function tableName(): string
    {
        return 'comments';
    }

    /**
     * {@inheritDoc}
     */
    public function rules(): array
    {
        return [
            ['comment', 'trim'],
            ['comment', 'required', 'message' => 'Напишите что-нибудь'],
            ['comment', function (string $attribute) {
                if (empty(strip_tags($this->comment))) {
                    $this->addError($attribute, 'Похоже, что в тексте, только HTML теги');
                }
            }],
            ['comment', 'string', 'max' => 500, 'tooLong' => "Комментарий не должен содержать больше 500 символов (сейчас - {$this->fieldLength()})"],
        ];
    }

    /**
     * Получает длину строки комментария.
     */
    private function fieldLength(): int
    {
        return (new StringService($this->comment))
            ->getLength();
    }
}
