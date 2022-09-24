<?php

use yii\db\Migration;

/**
 * Class m220924_083107_add_fkey_author_to_posts
 */
class m220924_083107_add_fkey_author_to_posts extends Migration
{
    public function up()
    {
        $this->addColumn(
            'posts',
            'author',
            $this->integer()->notNull(),
        );
        $this->addForeignKey(
            'author',
            'posts',
            'author',
            'users',
            'id',
        );
    }

    public function down()
    {
        $this->dropForeignKey(
            'author',
            'posts',
        );
        $this->dropColumn('posts', 'author');
    }
}