<?php

use yii\db\Expression;
use yii\db\Migration;

/**
 * Создает таблицу жалоб на сайте.
 */
class m221009_123414_create_complaints_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function up()
    {
        $this->execute("CREATE TYPE objects AS ENUM ('user', 'post', 'comment')");
        $this->createTable('complaints', [
            'id' => $this->primaryKey(),
            'object' => 'objects NOT NULL',
            'object_id' => $this->integer()->notNull(),
            'sender_id' => $this->integer()->notNull(),
            'sender_username' => $this->string(30)->notNull(),
            'complaint' => $this->string(250)->notNull(),
            'datetime' => $this->timestamp()->defaultValue(new Expression("NOW()")),
        ]);
        $this->addForeignKey(
            'sender_id_fk',
            'complaints',
            'sender_id',
            'users',
            'id',
        );
        $this->addForeignKey(
            'sender_username_fk',
            'complaints',
            'sender_username',
            'users',
            'username',
        );
    }

    /**
     * {@inheritdoc}
     */
    public function down()
    {
        $this->dropForeignKey('sender_username_fk', 'complaints');
        $this->dropForeignKey('sender_id_fk', 'complaints');
        $this->dropTable('complaints');
        $this->execute('DROP TYPE objects');
    }
}
