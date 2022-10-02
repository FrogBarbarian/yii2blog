<?php

use yii\db\Migration;

/**
 * Создает таблицу со статистикой сайта для отдельного пользователя.
 */
class m221002_065715_table_statistic_for_user extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function up()
    {
        $this->createTable('statistics', [
            'id' => $this->primaryKey(),
            'owner' => $this->string(30)->notNull()->unique(),
            'views' => $this->integer()->defaultValue(0),
            'posts' => $this->integer()->defaultValue(0),
            'comments' => $this->integer()->defaultValue(0),
            'likes' => $this->integer()->defaultValue(0),
            'dislikes' => $this->integer()->defaultValue(0),
            'rating' => $this->integer()->defaultValue(0),
        ]);

        $this->addForeignKey(
            'owner_fk',
            'statistics',
            'owner',
            'users',
            'login',
        );
    }

    /**
     * {@inheritdoc}
     */
    public function down()
    {
        $this->dropTable('statistic');
    }
}
