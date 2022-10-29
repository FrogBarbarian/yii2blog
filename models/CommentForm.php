<?php

declare(strict_types = 1);

namespace app\models;

use src\services\StringService;
use yii\db\ActiveRecord;

class CommentForm extends ActiveRecord
{
    /**
     * @var string Текст комментария.
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
     * Получает длину строки жалобы.
     * @return int
     */
    private function fieldLength(): int
    {
        $attribute = $_POST['CommentForm']['comment'] ?? '';

        return (new StringService($attribute))
            ->getLength();
    }
}
