<?php

use yii\db\Migration;

/**
 * Добавляет в таблицу постов и пользователей параметр возможности комментировать.
 */
class m221003_080957_add_comment_workflow extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function up()
    {
        $this->addColumn('users', 'can_comment', $this->boolean()->defaultValue(true));
        $this->addColumn('posts', 'is_commentable', $this->boolean()->defaultValue(true));
    }

    /**
     * {@inheritdoc}
     */
    public function down()
    {
        $this->dropColumn('posts', 'is_commentable');
        $this->dropColumn('users', 'can_comment');
    }
}
