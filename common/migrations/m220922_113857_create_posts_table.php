<?php

use yii\db\Migration;

/**
 * Таблица с постами пользователей.
 */
class m220922_113857_create_posts_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function up()
    {
        $this->createTable('posts', [
            'id' => $this->primaryKey(),
            'title' => $this->string(100)->notNull(),
            'body' => $this->text()->notNull(),
            'viewed' => $this->integer()->notNull()->defaultValue(0),
        ]);
    }


    public function down()
    {
        $this->dropTable('posts');
    }
}
