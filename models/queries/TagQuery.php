<?php

declare(strict_types = 1);

namespace app\models\queries;

use yii\db\ActiveQuery;

/**
 * Запросы к тегам.
 */
class TagQuery extends ActiveQuery
{
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
