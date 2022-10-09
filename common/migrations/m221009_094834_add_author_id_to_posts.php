<?php

use yii\db\Migration;

/**
 * Добавляет колонку с ID автора в таблицу постов.
 */
class m221009_094834_add_author_id_to_posts extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function up()
    {
        $this->addColumn('posts', 'author_id', $this->integer()->notNull());
        $this->addForeignKey('author_id_fk', 'posts', 'author_id', 'users', 'id');

    }

    /**
     * {@inheritdoc}
     */
    public function down()
    {
        $this->dropForeignKey('author_id_fk', 'posts');
        $this->dropColumn('posts', 'author_id');
    }
}
