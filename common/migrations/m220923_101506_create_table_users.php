<?php

use yii\db\Migration;

/**
 * Class m220923_101506_create_table_users
 */
class m220923_101506_create_table_users extends Migration
{
    public function up()
    {
        $this->createTable('users', [
            'id' => $this->primaryKey(),
            'login' => $this->string(30)->notNull()->unique(),
            'email' => $this->text()->notNull()->unique(),
            'password' => $this->text()->notNull(),
            'isAdmin' => $this->boolean()->defaultValue(false),
        ]);
    }

    public function down()
    {
        $this->dropTable('users');
    }
}
