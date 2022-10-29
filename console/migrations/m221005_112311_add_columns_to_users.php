<?php

use yii\db\Migration;

/**
 * Добавляет столбцы в таблицу пользователей.
 */
class m221005_112311_add_columns_to_users extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function up()
    {
        $this->addColumn('users', 'can_write_messages', $this->boolean()->defaultValue(true)->notNull());
        $this->addColumn('users', 'can_write_posts', $this->boolean()->defaultValue(true)->notNull());
        $this->addColumn('users', 'is_banned', $this->boolean()->defaultValue(false)->notNull());
    }

    /**
     * {@inheritdoc}
     */
    public function down()
    {
        $this->dropColumn('users', 'can_write_messages');
        $this->dropColumn('users', 'can_write_posts');
        $this->dropColumn('users', 'is_banned');
    }
}
