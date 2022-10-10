<?php

declare(strict_types = 1);

namespace app\models;

use src\services\StringService;
use yii\db\ActiveRecord;

class ComplaintForm extends ActiveRecord
{
    /**
     * @var string Текст жалобы.
     */
    public string $complaint = '';

    /**
     * {@inheritDoc}
     */
    public static function tableName(): string
    {
        return 'complaints';
    }

    /**
     * {@inheritDoc}
     */
    public function rules(): array
    {
        return [
            ['complaint', 'required', 'message' => 'Изложите суть жалобы'],
            ['complaint', 'trim'],
            [
                'complaint',
                'string',
                'length' => [15, 240],
                'tooShort' => "Жалоба должна содержать не менее 15 символов (сейчас - {$this->fieldLength()})",
                'tooLong' => "Жалоба должна содержать не больше 240 символов (сейчас - {$this->fieldLength()})",
            ],
        ];
    }
    
    /**
     * Получает длину строки жалобы.
     * @return int
     */
    private function fieldLength(): int
    {
        $attribute = $_POST['ComplaintForm']['complaint'] ?? '';

        return (new StringService($attribute))
            ->getLength();
    }
}