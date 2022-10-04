<?php

declare(strict_types = 1);

namespace app\models;

use yii\db\ActiveRecord;

class CommentForm extends ActiveRecord
{
    public string $comment = '';

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
            ['comment', 'string', 'max' => 500, 'tooLong' => 'Комментарий не должен содержать больше 500 символов'],
        ];
    }
}
