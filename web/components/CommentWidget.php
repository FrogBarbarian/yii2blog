<?php

declare(strict_types=1);

namespace app\components;

use app\models\Comment;
use app\models\User;
use yii\base\Widget;

/**
 * Комментарий.
 */
class CommentWidget extends Widget
{
    public ?User $user = null;
    public ?Comment $comment = null;

    /**
     * {@inheritDoc}
     */
    public function run(): string
    {
        return $this->render('comment', [
            'user' => $this->user,
            'comment' => $this->comment,
        ]);
    }
}