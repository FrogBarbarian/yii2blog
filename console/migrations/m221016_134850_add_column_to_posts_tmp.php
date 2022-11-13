<?php

use yii\db\Migration;

/**
 * Добавляет столбцы в таблицу
 */
class m221016_134850_add_column_to_posts_tmp extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function up()
    {
        $this->addColumn('tmp_posts', 'old_title', $this->string(150)->defaultValue(null));
    }

    /**
     * {@inheritdoc}
     */
    public function down()
    {
        $this->dropColumn('tmp_posts', 'old_tags');
    }
}
