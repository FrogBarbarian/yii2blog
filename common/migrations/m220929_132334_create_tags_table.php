<?php

use yii\db\Migration;

/**
 * Создает таблицу для хранения списка тегов к постам.
 */
class m220929_132334_create_tags_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function up()
    {
        $this->createTable('tags', [
            'id' => $this->primaryKey(),
            'tag' => $this->text()->notNull(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function down()
    {
        $this->dropTable('tags');
    }
}
