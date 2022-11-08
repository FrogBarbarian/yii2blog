<?php

declare(strict_types = 1);

namespace app\models;

use app\models\queries\TagQuery;
use yii\db\ActiveRecord;

/**
 * Тег.
 */
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
     * @return int ID.
     */
    public function getId(): int
    {
        return $this->getAttribute('id');
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

    /**
     * Проверяет набор тегов у создаваемого поста.
     * Если тег новый - создает его.
     * Иначе увеличивает количество использований тега на 1.
     */
    public static function checkWhenCreatePost(array $tags)
    {
        foreach ($tags as $tag) {
            $tagObj = self::find()
                ->byTag($tag)
                ->one();

            if ($tagObj === null) {
                $tagObj = new self();
                $tagObj
                    ->setTag($tag)
                    ->save();
            } else {
                $tagObj
                    ->increaseAmountOfUse()
                    ->save();
            }
        }
    }

    /**
     * Проверяет набор тегов у обновляемого поста.
     * @param array $oldTags Теги до обновления.
     * @param array $newTags Теги после обновления.
     */
    public static function checkWhenUpdatePost(array $oldTags, array $newTags)
    {
        $unsetTags = array_diff($oldTags, $newTags);
        $setTags = array_diff($newTags, $oldTags);

        foreach ($unsetTags as $tag) {
            $tagObj = self::find()
                ->byTag($tag)
                ->one();
            $tagObj
                ->decreaseAmountOfUse()
                ->save();
        }

        self::checkWhenCreatePost($setTags);
    }
}
