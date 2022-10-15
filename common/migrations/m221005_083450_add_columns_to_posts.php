<?php

use yii\db\Migration;

/**
 * Добавляет колонки к таблице постов.
 */
class m221005_083450_add_columns_to_posts extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function up()
    {
        $this->addColumn('posts', 'likes', $this->integer()->defaultValue(0)->notNull());
        $this->addColumn('posts', 'dislikes', $this->integer()->defaultValue(0)->notNull());
        $this->addColumn('posts', 'rating', $this->integer()->defaultValue(0)->notNull());
    }

    /**
     * {@inheritdoc}
     */
    public function down()
    {
        $this->dropColumn('posts', 'likes');
        $this->dropColumn('posts', 'dislikes');
        $this->dropColumn('posts', 'rating');
    }
}
