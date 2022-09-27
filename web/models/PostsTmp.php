<?php

declare(strict_types = 1);

namespace app\models;

class PostsTmp extends Posts
{
    /**
     * {@inheritDoc}
     */
    public static function tableName(): string
    {
        return 'posts_tmp';
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
