<?php

use yii\db\Expression;
use yii\db\Migration;

/**
 * Добавляет в таблицу с постами и таблицу для временного хранения новых
 * и отредактированных постов пользователей новые столбцы - теги, главное изображение, дата и время создания.
 */
class m220928_112607_add_columns_to_posts extends Migration
{
    /**
     * {@inheritDoc}
     */
    public function up()
    {
        $posts = 'posts';
        $postsTmp = 'tmp_posts';
        $this->addColumn($posts, 'tags', $this->text()->notNull());
        $this->addColumn($posts, 'datetime', $this->timestamp()->defaultValue(new Expression("NOW()")));
        $this->addColumn($postsTmp, 'tags', $this->text()->notNull());
        $this->addColumn($postsTmp, 'datetime', $this->timestamp()->defaultValue(new Expression("NOW()")));
    }

    /**
     * {@inheritDoc}
     */
    public function down()
    {
        $posts = 'posts';
        $postsTmp = 'tmp_posts';
        $this->dropColumn($posts, 'tags');
        $this->dropColumn($posts, 'datetime');
        $this->dropColumn($postsTmp, 'tags');
        $this->dropColumn($postsTmp, 'datetime');
    }
}
