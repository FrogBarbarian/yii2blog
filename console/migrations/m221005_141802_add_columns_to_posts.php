<?php

use yii\db\Migration;

/**
 * Добавляет столбцы в таблицу для временного хранения новых
 * и отредактированных постов пользователей
 */
class m221005_141802_add_columns_to_posts extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function up()
    {
        $this->addColumn('posts', 'users_liked', $this->text()->defaultValue('')->notNull());
        $this->addColumn('posts', 'users_disliked', $this->text()->defaultValue('')->notNull());
    }

    /**
     * {@inheritdoc}
     */
    public function down()
    {
        $this->dropColumn('posts', 'users_liked');
        $this->dropColumn('posts', 'users_disliked');
    }
}
