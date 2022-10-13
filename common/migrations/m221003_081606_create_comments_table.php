<?php

use yii\db\Migration;

/**
 * Создает таблицу для хранения комментариев к постам.
 */
class m221003_081606_create_comments_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function up()
    {
        $this->createTable('comments', [
            'id' => $this->primaryKey(),
            'post_id' => $this->integer()->notNull(),
            'author' => $this->string(30)->notNull(),
            'author_id' => $this->integer()->notNull(),
            'comment' => $this->text()->notNull(),
            'datetime' => $this->dateTime()->defaultValue(new \yii\db\Expression("CURRENT_TIMESTAMP(0)"))->notNull(),
            'likes' => $this->integer()->defaultValue(0)->notNull(),
            'dislikes' => $this->integer()->defaultValue(0)->notNull(),
            'rating' => $this->integer()->defaultValue(0)->notNull(),
            'users_liked' => $this->text()->defaultValue('')->notNull(),
            'users_disliked' => $this->text()->defaultValue('')->notNull(),
            'is_deleted' => $this->boolean()->defaultValue(false)->notNull(),
        ]);

        $this->addForeignKey('author_fk', 'comments', 'author', 'users', 'login');
        $this->addForeignKey('author_id_fk', 'comments', 'author_id', 'users', 'id');
        $this->addForeignKey('post_id_fk', 'comments', 'post_id', 'posts', 'id');
    }

    /**
     * {@inheritdoc}
     */
    public function down()
    {
        $this->dropTable('comments');
    }
}
