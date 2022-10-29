<?php

use yii\db\Migration;

/**
 * В таблицу с постами добавляется столбец автор.
 * Он является внешним ключом поля имя пользователя таблицы пользователей.
 */
class m220924_083107_add_fkey_author_to_posts extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function up()
    {
        $this->addColumn(
            'posts',
            'author',
            $this->string(30)->notNull(),
        );
        $this->addForeignKey(
            'author_fk',
            'posts',
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
        $this->dropForeignKey(
            'author_fk',
            'posts',
        );
        $this->dropColumn('posts', 'author');
    }
}
