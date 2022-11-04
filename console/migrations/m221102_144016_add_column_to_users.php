<?php

use yii\db\Migration;

/**
 * Добавляет колонку в таблицу с пользователями
 */
class m221102_144016_add_column_to_users extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->addColumn('users', 'is_pm_open', $this->boolean()->defaultValue(true)->notNull());
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropColumn('users', 'is_pm_open');
    }
}
