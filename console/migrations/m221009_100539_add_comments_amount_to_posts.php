<?php

use yii\db\Migration;

/**
 * Добавляет колонку с количеством комментариев в таблицу постов.
 */
class m221009_100539_add_comments_amount_to_posts extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function up()
    {
        $this->addColumn('posts', 'comments_amount', $this->integer()->defaultValue(0)->notNull());
    }

    /**
     * {@inheritdoc}
     */
    public function down()
    {
        $this->dropColumn('posts', 'comments_amount');
    }
}
