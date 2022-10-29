<?php

declare(strict_types = 1);

namespace app\models\queries;

use yii\db\ActiveQuery;

class TagQuery extends ActiveQuery
{
    /**
     * Поиск по ID.
     */
    public function byId(int $id): self
    {
        return $this->where(['id' => $id]);
    }

    /**
     * Поиск по символам.
     */
    public function byChars(string $chars): self
    {
        return $this->where(['ILIKE', 'tag', $chars]);
    }

    /**
     * Поиск по точному совпадению названия тега (без учета регистра).
     */
    public function byTag(string $tag): self
    {
        return $this->where(['ILIKE', 'tag', "%$tag", false]);
    }
}
