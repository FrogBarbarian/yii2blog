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
}