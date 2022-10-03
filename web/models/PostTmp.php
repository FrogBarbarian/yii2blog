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
        return $this->getAttribute('isNew');
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
        $this->setAttribute('isNew', $isNew);

        return $this;
    }
}
