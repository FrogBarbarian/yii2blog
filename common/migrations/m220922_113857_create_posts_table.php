<?php

use yii\db\Migration;

/**
 * Handles the creation of table `posts`.
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
            'title' => $this->string(50)->notNull(),
            'body' => $this->text()->notNull(),
            'viewed' => $this->integer()->notNull()->defaultValue(0),
        ]);
    }


    public function down()
    {
        $this->dropTable('posts');
    }
}
