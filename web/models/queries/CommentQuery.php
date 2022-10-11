<?php

declare(strict_types = 1);

namespace app\models\queries;

use yii\db\ActiveQuery;

class CommentQuery extends ActiveQuery
{
    /**
     * Поиск по ID поста, к которому принадлежит комментарий.
     */
    public function byPostId(int $postId): self
    {
        return $this->where(['post_id' => $postId]);
    }
    /**
     * Поиск по ID.
     */
    public function byId(int $id): self
    {
        return $this->where(['id' => $id]);
    }

    /**
     * Сортирует по ID в прямом порядке.
     */
    public function orderAscById(): self
    {
        return $this->orderBy(['id' => SORT_ASC]);
    }

    /**
     * Сортирует по ID в обратном порядке.
     */
    public function orderDescById(): self
    {
        return $this->orderBy(['id' => SORT_DESC]);
    }
}
