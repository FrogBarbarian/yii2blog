<?php

use yii\db\Expression;
use yii\db\Migration;

/**
 * Создает таблицу с пользователями.
 */
class m220923_101506_create_table_users extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function up()
    {
        $this->createTable('users', [
            'id' => $this->primaryKey(),
            'username' => $this->string(30)->notNull()->unique(),
            'auth_key' => $this->string(32)->notNull(),
            'password_hash' => $this->string()->notNull(),
            'password_reset_token' => $this->string()->unique(),
            'email' => $this->text()->notNull()->unique(),
            'isAdmin' => $this->boolean()->defaultValue(false)->notNull(),
            'created' => $this->timestamp()->defaultValue(new Expression("NOW()")),
            'status' => $this->smallInteger()->defaultValue(10)->notNull(),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function down()
    {
        $this->dropTable('users');
    }
}
