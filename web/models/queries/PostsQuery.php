<?php

namespace app\models\queries;

use yii\db\ActiveQuery;

class PostsQuery extends ActiveQuery
{
    /**
     * Поиск по ID поста.
     */
    public function byId(int $id): self
    {
        return $this->where(['id' => $id]);
    }

    /**
     * Сортирует по ID в обратном порядке.
     */
    public function descById(): self
    {
        return $this->orderBy(['id' => SORT_DESC]);
    }
}
