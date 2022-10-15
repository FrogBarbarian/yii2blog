<?php

use yii\db\Migration;

/**
 * Создает таблицу со статистикой пользователей.
 */
class m221002_065715_table_statistics_for_user extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function up()
    {
        $this->createTable('statistics', [
            'id' => $this->primaryKey(),
            'owner' => $this->string(30)->notNull()->unique(),
            'views' => $this->integer()->defaultValue(0)->notNull(),
            'posts' => $this->integer()->defaultValue(0)->notNull(),
            'comments' => $this->integer()->defaultValue(0)->notNull(),
            'likes' => $this->integer()->defaultValue(0)->notNull(),
            'dislikes' => $this->integer()->defaultValue(0)->notNull(),
            'rating' => $this->integer()->defaultValue(0)->notNull(),
        ]);

        $this->addForeignKey(
            'owner_fk',
            'statistics',
            'owner',
            'users',
            'username',
        );
    }

    /**
     * {@inheritdoc}
     */
    public function down()
    {
        $this->dropForeignKey('owner_fk', 'statistics');
        $this->dropTable('statistics');
    }
}
