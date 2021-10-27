<?php

use yii\db\Migration;


class m211014_113606_create_garages_users_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%garages_users}}', [
            'id' => $this->primaryKey(),

            'garage_id' => $this->integer()->notNull(),
            'user_id' => $this->integer()->notNull()
        ]);
        $this->addForeignKey('garages_users_garages_fk_constraint', '{{garages_users}}',
            'garage_id', '{{garages}}', 'id', 'CASCADE');
        $this->addForeignKey('garages_users_users_fk_constraint', '{{garages_users}}',
            'user_id', '{{users}}', 'id', 'CASCADE');

        $this->createIndex(
            'garages_users_unique',
            '{{%garages_users}}',
            ['user_id', 'garage_id'],
            true
        );

    }


    public function safeDown()
    {
//        $this->dropIndex('garages_users_unique','{{%garages_users}}');
        $this->dropForeignKey('garages_users_garages_fk_constraint', '{{garages_users}}');
        $this->dropForeignKey('garages_users_users_fk_constraint', '{{garages_users}}');
        $this->dropTable('{{%garages_users}}');
    }
}
