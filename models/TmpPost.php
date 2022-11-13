<?php

declare(strict_types = 1);

namespace app\models;

use app\models\queries\TmpPostQuery;

/**
 * Модель временного поста.
 */
class TmpPost extends Post
{
    /**
     * {@inheritDoc}
     */
    public static function tableName(): string
    {
        return 'tmp_posts';
    }

    /**
     * {@inheritDoc}
     */
    public static function find(): TmpPostQuery
    {
        return new TmpPostQuery(self::class);
    }

    /**
     * Новый или отредактированный.
     */
    public function setIsNew(bool $isNew): self
    {
        $this->setAttribute('is_new', $isNew);

        return $this;
    }

    /**
     * @return bool Новый или отредактированный.
     */
    public function getIsNew(): bool
    {
        return $this->getAttribute('is_new');
    }

    /**
     * ID оригинального поста.
     */
    public function setUpdateId(int $updateId): self
    {
        $this->setAttribute('update_id', $updateId);

        return $this;
    }

    /**
     * @return int ID оригинального поста.
     */
    public function getUpdateId(): int
    {
        return $this->getAttribute('update_id');
    }

    /**
     * Теги до изменений.
     */
    public function setOldTags(string $tags): self
    {
        $this->setAttribute('old_tags', $tags);

        return $this;
    }

    /**
     * @return string Теги до сохранения формы.
     */
    public function getOldTags(): string
    {
        return $this->getAttribute('old_tags');
    }
}
