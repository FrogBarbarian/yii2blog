<?php

declare(strict_types = 1);

namespace app\models;

use yii\db\ActiveRecord;
use Yii;

/**
 * Форма отправки жалобы.
 */
class ComplaintForm extends ActiveRecord
{
    /**
     * @var string Текст жалобы.
     */
    public string $complaint = '';
    public string $objectType = '';
    public int $objectId = 0;

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
            [['objectType', 'objectId'], 'safe'],
            ['complaint', 'required', 'message' => 'Изложите суть жалобы'],
            ['complaint', 'trim'],
            ['complaint', 'checkComplaintForExist'],
            [
                'complaint',
                'string',
                'length' => [10, 250],
                'tooShort' => "Жалоба должна содержать не менее 10 символов",
                'tooLong' => "Жалоба должна содержать не больше 250 символов",
            ],
        ];
    }

    public function checkComplaintForExist()
    {
        $senderId = Yii::$app
            ->user
            ->getId();
        $complaint = Complaint::find()
            ->where(['sender_id' => $senderId])
            ->andWhere(['object' => $this->objectType])
            ->andWhere(['object_id' => $this->objectId])
            ->one();

        if ($complaint !== null) {
            $this->addError('complaint', 'Вы уже отправляли подобную жалобу');
        }
    }
}
