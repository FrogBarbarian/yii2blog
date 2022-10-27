<?php

use yii\db\Migration;

/**
 *  Создает таблицу для сообщений пользователей.
 */
class m221027_081421_create_table_messages extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->execute("CREATE TYPE message_status AS ENUM ('draft', 'sent', 'received', 'read', 'delete')");
        $this->createTable('messages', [
            'id' => $this->primaryKey(),
            'sender_username' => $this->string(30)->notNull(),
            'sender_id' => $this->integer()->notNull(),
            'recipient_username' => $this->string(30)->notNull(),
            'recipient_id' => $this->integer()->notNull(),
            'subject' => $this->string(100)->notNull(),
            'content' => $this->text()->notNull(),
            'status' => 'message_status NOT NULL',
        ]);
        $this->addForeignKey(
            'sender_username_fk',
            'messages',
            'sender_username',
            'users',
            'username',
        );
        $this->addForeignKey(
            'recipient_username_fk',
            'messages',
            'recipient_username',
            'users',
            'username',
        );
        $this->addForeignKey(
            'sender_id_id_fk',
            'messages',
            'sender_id',
            'users',
            'id',
        );
        $this->addForeignKey(
            'recipient_id_fk',
            'messages',
            'recipient_id',
            'users',
            'id',
        );
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropForeignKey('user_id_fk', 'messages');
        $this->dropForeignKey('username_fk', 'messages');
        $this->dropTable('messages');
        $this->execute('DROP TYPE message_status');
    }
}
