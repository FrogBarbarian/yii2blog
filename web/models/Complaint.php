<?php

declare(strict_types = 1);

namespace app\models;

use app\models\queries\ComplaintQuery;
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
     * {@inheritDoc}
     */
    public static function find(): ComplaintQuery
    {
        return new ComplaintQuery(self::class);
    }

    /**
     * Тип объекта.
     */
    public function setObject(string $object): self
    {
        $this->setAttribute('object', $object);

        return $this;
    }

    /**
     * ID объекта.
     */
    public function setObjectId(int $objectId): self
    {
        $this->setAttribute('object_id', $objectId);

        return $this;
    }

    /**
     * ID отправителя.
     */
    public function setSenderId(int $senderId): self
    {
        $this->setAttribute('sender_id', $senderId);

        return $this;
    }

    /**
     * ID отправителя.
     */
    public function setSenderUsername(string $senderUsername): self
    {
        $this->setAttribute('sender_username', $senderUsername);

        return $this;
    }

    /**
     * Текст.
     */
    public function setComplaint(string $complaint): self
    {
        $this->setAttribute('complaint', $complaint);

        return $this;
    }

    /**
     * @return string Текст.
     */
    public function getComplaint(): string
    {
        return $this->getAttribute('complaint');
    }

    /**
     * @return string Объект.
     */
    public function getObject(): string
    {
        return $this->getAttribute('object');
    }

    /**
     * @return string Дата и время написания.
     */
    public function getDatetime(): string
    {
        return $this->getAttribute('datetime');
    }

    /**
     * @return int ID.
     */
    public function getId(): int
    {
        return $this->getAttribute('id');
    }

    /**
     * @return int ID объекта.
     */
    public function getObjectId(): int
    {
        return $this->getAttribute('object_id');
    }

    /**
     * @return int ID отправителя.
     */
    public function getSenderId(): int
    {
        return $this->getAttribute('sender_id');
    }

    /**
     * @return string  Имя пользователя отправителя.
     */
    public function getSenderUsername(): string
    {
        return $this->getAttribute('sender_username');
    }
}
