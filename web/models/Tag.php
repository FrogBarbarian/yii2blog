<?php

declare(strict_types = 1);

namespace app\models;

use app\models\queries\TagQuery;
use yii\db\ActiveRecord;

class Tag extends ActiveRecord
{
    /**
     * {@inheritDoc}
     */
    public static function tableName(): string
    {
        return 'tags';
    }

    /**
     * {@inheritDoc}
     */
    public static function find(): TagQuery
    {
        return new TagQuery(self::class);
    }

    /**
     * @return string Тег.
     */
    public function getTag(): string
    {
        return $this->getAttribute('tag');
    }

    /**
     * Тег.
     */
    public function setTag(string $tag): self
    {
        $this->setAttribute('tag', $tag);

        return $this;
    }

    /**
     * @return int Количество использования.
     */
    public function getAmountOfUses(): int
    {
        return $this->getAttribute('amount_of_uses');
    }

    /**
     * Количество использования.
     */
    private function setAmountOfUses(int $amount)
    {
        $this->setAttribute('amount_of_uses', $amount);
    }

    /**
     * Увеличивает количество использований тега на $value.
     * @param int $value = 1.
     */
    public function increaseAmountOfUse(int $value = 1): self
    {
        $this->setAmountOfUses($this->getAmountOfUses() + $value);

        return $this;
    }

    /**
     * Уменьшает количество использований тега на $value.
     * @param int $value = 1.
     */
    public function decreaseAmountOfUse(int $value = 1): self
    {
        $this->setAmountOfUses($this->getAmountOfUses() - $value);

        return $this;
    }
}
