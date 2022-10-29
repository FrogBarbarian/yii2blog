<?php

use yii\db\Migration;

/**
 * Class m221018_141052_add_column_to_tmp_posts
 */
class m221018_141052_add_column_to_tmp_posts extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function up()
    {
        $this->addColumn('tmp_posts', 'author_id', $this->integer()->notNull());
        $this->addForeignKey(
            'author_id_fk',
            'tmp_posts',
            'author_id',
            'users',
            'id',
        );
    }

    /**
     * {@inheritdoc}
     */
    public function down()
    {
        $this->dropForeignKey('author_id_fk', 'tmp_posts');
        $this->dropColumn('tmp_posts', 'author_id');
    }
}
