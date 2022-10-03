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
            'comment' => $this->string()->notNull(),
            'datetime' => $this->dateTime()->defaultValue(new \yii\db\Expression("NOW()")),
            'likes' => $this->integer()->defaultValue(0),
            'dislikes' => $this->integer()->defaultValue(0),
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
