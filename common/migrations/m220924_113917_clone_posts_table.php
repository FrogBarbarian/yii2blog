<?php

use yii\db\Migration;

/**
 * Class m220924_113917_clone_posts_table
 */
class m220924_113917_clone_posts_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function up()
    {
        $this->createTable('posts_tmp', [
            'id' => $this->primaryKey(),
            'title' => $this->string(100)->notNull(),
            'body' => $this->text()->notNull(),
            'author' => $this->integer()->notNull(),
            'isNew' => $this->boolean()->notNull()->defaultValue(true),
        ]);

        $this->addForeignKey(
            'author',
            'posts_tmp',
            'author',
            'users',
            'id',
        );
    }

    /**
     * {@inheritdoc}
     */
    public function down()
    {
        $this->dropTable('posts_tmp');
    }
}
