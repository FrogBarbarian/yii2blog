<?php

use yii\db\Migration;

/**
 * Добавляет bool поле is_hidden в таблицу users.
 * Она отражает настройки профиля пользователя - скрыт или виден.
 */
class m220930_150738_add_ishidden_column_to_users extends Migration
{
    /**
     * {@inheritDoc}
     */
    public function up()
    {
        $this->addColumn('users', 'is_hidden', $this->boolean()->defaultValue(false));
    }

    /**
     * {@inheritDoc}
     */
    public function down()
    {
        $this->dropColumn('users', 'is_hidden');
    }
}
