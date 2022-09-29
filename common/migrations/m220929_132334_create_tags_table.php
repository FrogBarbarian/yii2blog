<?php

use yii\db\Migration;

/**
 * Создаем таблицу, в которой будут храниться созданные тэги для постов.
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
            'tag' => $this->string()->notNull(),
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
