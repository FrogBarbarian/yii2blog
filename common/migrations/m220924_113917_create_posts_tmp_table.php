<?php

use yii\db\Migration;

/**
 * Создает таблицу для временного хранения новых и отредактированных постов пользователей,
 * которые необходимо проверить администратору перед публикацией.
 */
class m220924_113917_create_posts_tmp_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function up()
    {
        $this->createTable('tmp_posts', [
            'id' => $this->primaryKey(),
            'title' => $this->string(150)->notNull(),
            'body' => $this->text()->notNull(),
            'author' => $this->string(30)->notNull(),
            'is_new' => $this->boolean()->defaultValue(true)->notNull(),
            'update_id' => $this->integer()->defaultValue(null),
        ]);
        $this->addForeignKey(
            'pt_author_fk',
            'posts_tmp',
            'author',
            'users',
            'username',
        );
    }

    /**
     * {@inheritdoc}
     */
    public function down()
    {
        $this->dropForeignKey('pt_author_fk', 'posts_tmp');
        $this->dropTable('tmp_posts');
    }
}
