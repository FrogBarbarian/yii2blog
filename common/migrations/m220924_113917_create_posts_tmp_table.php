<?php

use yii\db\Migration;

/**
 * Class m220924_113917_create_posts_tmp_table
 */
class m220924_113917_create_posts_tmp_table extends Migration
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
            'author' => $this->string(30)->notNull(),
            'isNew' => $this->boolean()->notNull()->defaultValue(true),
            'update_id' => $this->integer(),
        ]);

        $this->addForeignKey(
            'pt_author_fk',
            'posts_tmp',
            'author',
            'users',
            'login',
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
