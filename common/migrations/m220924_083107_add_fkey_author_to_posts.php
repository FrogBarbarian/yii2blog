<?php

use yii\db\Migration;

/**
 * Class m220924_083107_add_fkey_author_to_posts
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
            'author',
            'posts',
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
        $this->dropForeignKey(
            'author',
            'posts',
        );
        $this->dropColumn('posts', 'author');
    }
}