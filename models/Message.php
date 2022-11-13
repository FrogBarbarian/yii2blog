<?php

declare(strict_types=1);

namespace app\models;

use app\models\queries\MessageQuery;
use src\services\StringService;
use yii\db\ActiveRecord;

/**
 * Модель сообщения.
 */
class Message extends ActiveRecord
{
    /**
     * {@inheritDoc}
     */
    public static function tableName(): string
    {
        return 'messages';
    }

    /**
     * {@inheritDoc}
     */
    public static function find(): MessageQuery
    {
        return new MessageQuery(self::className());
    }

    /**
     * @return int ID.
     */
    public function getId(): int
    {
        return $this->getAttribute('id');
    }

    /**
     * Имя отправителя.
     */
    public function setSenderUsername(string $username): self
    {
        $this->setAttribute('sender_username', $username);

        return $this;
    }

    /**
     * @return string Имя отправителя.
     */
    public function getSenderUsername(): string
    {
        return $this->getAttribute('sender_username');
    }

    /**
     * Имя получателя.
     */
    public function setRecipientUsername(string $username): self
    {
        $this->setAttribute('recipient_username', $username);

        return $this;
    }

    /**
     * @return string Имя получателя.
     */
    public function getRecipientUsername(): string
    {
        return $this->getAttribute('recipient_username');
    }

    /**
     * Тема.
     */
    public function setSubject(string $subject): self
    {
        $this->setAttribute('subject', (new StringService($subject))->prepareToSave());

        return $this;
    }

    /**
     * @return string Тема.
     */
    public function getSubject(): string
    {
        return $this->getAttribute('subject');
    }

    /**
     * Содержание.
     */
    public function setContent(string $content): self
    {
        $this->setAttribute('content', (new StringService($content))->prepareToSave());

        return $this;
    }

    /**
     * @return string Содержание.
     */
    public function getContent(): string
    {
        return $this->getAttribute('content');
    }

    /**
     * Статус отправителя.
     */
    public function setSenderStatus(string $status): self
    {
        $this->setAttribute('sender_status', $status);

        return $this;
    }

    /**
     * @return string Статус отправителя.
     */
    public function getSenderStatus(): string
    {
        return $this->getAttribute('sender_status');
    }

    /**
     * Статус получателя.
     */
    public function setRecipientStatus(string $status): self
    {
        $this->setAttribute('recipient_status', $status);

        return $this;
    }

    /**
     * @return string Статус получателя.
     */
    public function getRecipientStatus(): string
    {
        return $this->getAttribute('recipient_status');
    }

    /**
     * @return string Время создания письма.
     */
    public function getTimestamp(): string
    {
        return $this->getAttribute('timestamp');
    }

    /**
     * Прочтено или нет.
     */
    public function setIsRead(bool $isRead): self
    {
        $this->setAttribute('is_read', $isRead);

        return $this;
    }

    /**
     * @return bool Прочтено или нет.
     */
    public function getIsRead(): bool
    {
        return $this->getAttribute('is_read');
    }
}
