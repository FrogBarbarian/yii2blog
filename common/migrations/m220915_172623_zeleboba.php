<?php

use yii\db\Migration;

/**
 * Class m220915_172623_zeleboba
 */
class m220915_172623_zeleboba extends Migration
{
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {
        $this->insert('post', [
            'title' => 'migration',
            'body' => 'this is for test migration and then down this migration',
        ]);
    }

    public function down()
    {
        $this->delete('post',[
            'title' => 'migration',
        ]);
    }
}
