<?php

use yii\db\Migration;

/**
 * Создаем таблицу для хранения новый постов и их редакций со стороны обычных пользователей.
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
            'title' => $this->string(150)->notNull(),
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
