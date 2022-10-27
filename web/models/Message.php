<?php

declare(strict_types=1);

namespace app\models;

use yii\db\ActiveRecord;

class Message extends ActiveRecord
{
    /**
     * {@inheritDoc}
     */
    public static function tableName(): string
    {
        return 'messages';
    }

}