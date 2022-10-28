<?php

use yii\db\Expression;
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
        $this->execute("CREATE TYPE message_status AS ENUM ('draft', 'sent', 'delete')");
        $this->createTable('messages', [
            'id' => $this->primaryKey(),
            'sender_username' => $this->string(30)->notNull(),
            'recipient_username' => $this->string(30)->notNull(),
            'subject' => $this->string(100)->notNull(),
            'content' => $this->text()->notNull(),
            'status' => "message_status NOT NULL DEFAULT 'sent'",
            'timestamp' => $this->timestamp()->defaultValue(new Expression("NOW()")),
            'is_read' => $this->boolean()->defaultValue(false)->notNull(),
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
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropForeignKey('recipient_username_fk', 'messages');
        $this->dropForeignKey('sender_username_fk', 'messages');
        $this->dropTable('messages');
        $this->execute('DROP TYPE message_status');
    }
}
