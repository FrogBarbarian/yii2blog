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
            ['comment', 'string', 'length' => ['max' => 500]],
        ];
    }
}