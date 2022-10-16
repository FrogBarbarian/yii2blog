<?php

declare(strict_types = 1);

namespace app\models;

use app\models\queries\PostTmpQuery;

class PostTmp extends Post
{
    /**
     * {@inheritDoc}
     */
    public static function tableName(): string
    {
        return 'posts_tmp';
    }

    /**
     * {@inheritDoc}
     */
    public static function find(): PostTmpQuery
    {
        return new PostTmpQuery(self::class);
    }

    /**
     * @return bool Новый пост|редакция поста.
     */
    public function getIsNew(): bool
    {
        return $this->getAttribute('is_new');
    }

    /**
     * @return int ID оригинального поста.
     */
    public function getUpdateId(): int
    {
        return $this->getAttribute('update_id');
    }

    /**
     * Устанавливает автора поста в таблице.
     * @param int $updateId
     * @return self
     */
    public function setUpdateId(int $updateId): self
    {
        $this->setAttribute('update_id', $updateId);

        return $this;
    }

    /**
     * Устанавливает новый или редактируемый пост в таблице.
     * @param bool $isNew
     * @return self
     */
    public function setIsNew(bool $isNew): self
    {
        $this->setAttribute('is_new', $isNew);

        return $this;
    }

    /**
     * Название до изменений.
     */
    public function setOldTitle(string $title): self
    {
        $this->setAttribute('old_title', $title);

        return $this;
    }

    /**
     * Содержание до изменений.
     */
    public function setOldBody(string $body): self
    {
        $this->setAttribute('old_body', $body);

        return $this;
    }

    /**
     * Теги до изменений.
     */
    public function setOldTags(string $tags): self
    {
        $this->setAttribute('old_tags', $tags);

        return $this;
    }
}
