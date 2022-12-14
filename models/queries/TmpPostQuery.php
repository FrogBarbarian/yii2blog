<?php

declare(strict_types = 1);

namespace app\models\queries;

/**
 * Запросы к временным постам.
 */
class TmpPostQuery extends PostQuery
{
    /**
     * Поиск по update_id поста.
     */
    public function byUpdatedId(int $updateId): self
    {
        return $this->where(['update_id' => $updateId]);
    }
}
