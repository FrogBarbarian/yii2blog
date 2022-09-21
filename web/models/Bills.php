<?php

namespace app\models;

use yii\db\ActiveRecord;

class Bills extends ActiveRecord
{
    public string $title = '';
    public string $amount = '';
    public string $term = '';

    public function rules(): array
    {
        return [
            [['title', 'amount', 'term'], 'trim'],
            ['title', 'required', 'message' => 'Введите название счета'],
            ['title', 'string', 'length' => [3, 20], 'tooShort' => 'Минимум 3 символа в названии счета','tooLong' => 'Максимум 20 символов в названии счета'],
            ['title', 'match', 'pattern' => '/^[\w\s-]+$/i', 'message' => 'В названии счета используются недопустимые символы'],
            ['amount', 'required', 'message' => 'Введите сумму на счету'],
            ['amount', 'number', 'min' => 0,'message' => 'Сумма должна быть написана цифрами', 'tooSmall' => 'Сумма должна быть не меньше 0'],
            ['term', 'default', 'value' => 0]
            //TODO: проверка на существование названия таблицы у текущего юзера
        ];
    }

    public static function tableName(): string
    {
        return 'bills';
    }
}