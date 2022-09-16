<?php

use yii\db\Migration;

/**
 * Class m220915_164217_new_tab_users
 */
class m220915_164217_new_tab_users extends Migration
{

    // Use up()/down() to run migration code without a transaction.
    public function up()
    {
        $this->createTable('post', [
            'id' => $this->primaryKey(),
            'title' => $this->string(12)->notNull()->unique(),
            'body' => $this->text()
        ]);
    }

    public function down()
    {
        $this->dropTable('post');
    }
}
