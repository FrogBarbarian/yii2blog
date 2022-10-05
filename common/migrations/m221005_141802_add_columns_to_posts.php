<?php

use yii\db\Migration;

/**
 * Добавляет два столбца в таблицу 'posts'.
 */
class m221005_141802_add_columns_to_posts extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function up()
    {
        $this->addColumn('posts', 'liked_by_users', $this->text()->defaultValue(''));
        $this->addColumn('posts', 'disliked_by_users', $this->text()->defaultValue(''));
    }

    /**
     * {@inheritdoc}
     */
    public function down()
    {
        $this->dropColumn('posts', 'liked_by_users');
        $this->dropColumn('posts', 'disliked_by_users');
    }
}
