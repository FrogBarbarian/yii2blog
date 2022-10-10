<?php

declare(strict_types = 1);

namespace app\models;

use yii\db\ActiveRecord;

class Complaint extends ActiveRecord
{
    /**
     * {@inheritDoc}
     */
    public static function tableName(): string
    {
        return 'complaints';
    }

    /**
     * Тип объекта жалобы.
     */
    public function setObject(string $object): self
    {
        $this->setAttribute('object', $object);

        return $this;
    }

    /**
     * ID объекта жалобы.
     */
    public function setObjectId(string $objectId): self
    {
        $this->setAttribute('object_id', $objectId);

        return $this;
    }

    /**
     * ID отправителя жалобы.
     */
    public function setSenderId(string $senderId): self
    {
        $this->setAttribute('sender_id', $senderId);

        return $this;
    }

    /**
     * Текст жалобы.
     */
    public function setComplaint(string $complaint): self
    {
        $this->setAttribute('complaint', $complaint);

        return $this;
    }
}
