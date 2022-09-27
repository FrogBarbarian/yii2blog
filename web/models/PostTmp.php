<?php

declare(strict_types = 1);

namespace app\models;

use app\models\queries\PostsTmpQuery;

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
    public static function find(): PostsTmpQuery
    {
        return new PostsTmpQuery(self::class);
    }

    /**
     * @return bool Новый пост|редакция поста.
     */
    public function getIsNew(): bool
    {
        return $this->getAttribute('isNew');
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
