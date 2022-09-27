<?php

namespace app\models\queries;

use yii\db\ActiveQuery;
use yii\db\Expression;

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
    public function orderDescById(): self
    {
        return $this->orderBy(['id' => SORT_DESC]);
    }

    /**
     * Рандомный поиск.
     */
    public function random(): self
    {
        return $this->orderBy(new Expression('random()'));
    }

}
