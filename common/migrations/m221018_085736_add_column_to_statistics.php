<?php

use yii\db\Migration;

/**
 *  Добавляет столбец с ID владельца статистики в таблице статистики.
 */
class m221018_085736_add_column_to_statistics extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function up()
    {
        $this->addColumn('statistics', 'owner_id', $this->integer()->unique()->notNull());
        $this->addForeignKey(
            'owner_id_fk',
            'statistics',
            'owner_id',
            'users',
            'id',
        );
    }

    /**
     * {@inheritdoc}
     */
    public function down()
    {
        $this->dropForeignKey('owner_id_fk', 'statistics');
        $this->dropColumn('statistics', 'owner_id');
    }
}
