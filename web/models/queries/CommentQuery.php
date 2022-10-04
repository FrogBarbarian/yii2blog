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
     * Сортирует по ID в прямом порядке.
     */
    public function orderAscById(): self
    {
        return $this->orderBy(['id' => SORT_ASC]);
    }
}