<?php

declare(strict_types = 1);

namespace app\models\queries;

use yii\db\ActiveQuery;
use yii\db\Expression;

class PostQuery extends ActiveQuery
{
    /**
     * Поиск по ID поста.
     */
    public function byId(int $id): self
    {
        return $this->where(['id' => $id]);
    }

    /**
     * Поиск по автору поста.
     */
    public function byAuthor(string $author): self
    {
        return $this->where(['author' => $author]);
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
