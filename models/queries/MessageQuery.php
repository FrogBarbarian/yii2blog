<?php

declare(strict_types=1);

namespace app\models\queries;

use yii\db\ActiveQuery;

/**
 * Запросы к сообщениям.
 */
class MessageQuery extends ActiveQuery
{
    /**
     * Фильтр по статусу.
     */
    public function byStatus(string $status, bool $isSender = true): self
    {
        $subject = $isSender ? 'sender' : 'recipient';

        return $this->andWhere(["{$subject}_status" => $status]);
    }

    /**
     * Фильтр по отправителю.
     */
    public function sentFrom(string $sender = null): self
    {
        if ($sender !== null) {
            return $this->andWhere(['sender_username' => $sender]);
        }

        return $this;
    }

    /**
     * Фильтр по получателю.
     */
    public function sentFor(string $recipient = null): self
    {
        if ($recipient !== null) {
            return $this->andWhere(['recipient_username' => $recipient]);
        }

        return $this;
    }

    /**
     * Сортировка по ID.
     */
     public function orderById(int $sort = SORT_DESC): self
     {
         return $this->orderBy(['id' => $sort]);
     }
}
