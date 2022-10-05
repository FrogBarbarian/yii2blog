<?php

use yii\db\Migration;

/**
 * Добавляет в таблицу с постами 'posts' и таблицу для хранения новых и измененных постов пользователей 'posts_tmp' новые столбцы.
 */
class m220928_112607_add_columns_to_posts extends Migration
{
    /**
     * {@inheritDoc}
     */
    public function up()
    {
        $posts = 'posts';
        $postsTmp = 'posts_tmp';
        $this->addColumn($posts, 'tags', $this->string()->notNull());
        $this->addColumn($posts, 'main_image', $this->string()->defaultValue(null));
        $this->addColumn($posts, 'date', $this->date()->defaultValue(new \yii\db\Expression("NOW()")));
        $this->addColumn($postsTmp, 'tags', $this->string()->notNull());
        $this->addColumn($postsTmp, 'main_image', $this->string()->defaultValue(null));
        $this->addColumn($postsTmp, 'date', $this->date()->defaultValue(new \yii\db\Expression("NOW()")));
    }

    /**
     * {@inheritDoc}
     */
    public function down()
    {
        $posts = 'posts';
        $postsTmp = 'posts_tmp';
        $this->dropColumn($posts, 'tags');
        $this->dropColumn($posts, 'main_image');
        $this->dropColumn($posts, 'date');
        $this->dropColumn($postsTmp, 'tags');
        $this->dropColumn($postsTmp, 'main_image');
        $this->dropColumn($postsTmp, 'date');
    }
}
