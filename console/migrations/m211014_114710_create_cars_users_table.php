<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%car_owners}}`.
 */
class m211014_114710_create_cars_users_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%cars_users}}', [
            'id' => $this->primaryKey(),

            'car_id' => $this->integer()->notNull(),
            'user_id' => $this->integer()->notNull(),

            'speed_bonus' => $this->integer()->unsigned()->defaultValue(0),
            'mobility_bonus' => $this->integer()->unsigned()->defaultValue(0),
            'brake_bonus' => $this->integer()->unsigned()->defaultValue(0),

            'level' => $this->integer()->unsigned()->defaultValue(0),
            'rank' => $this->integer()->unsigned()->defaultValue(0)
        ]);

        $this->addForeignKey('cars_users_cars_fk_constraint', '{{cars_users}}',
            'car_id', '{{cars}}', 'id', 'CASCADE');
        $this->addForeignKey('cars_users_users_fk_constraint', '{{cars_users}}',
            'user_id', '{{users}}', 'id', 'CASCADE');

        $this->createIndex(
            'cars_users_unique',
            '{{%cars_users}}',
            ['user_id', 'car_id'],
            true
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
//        $this->dropIndex('cars_users_unique','{{%cars_users}}');
        $this->dropForeignKey('cars_users_users_fk_constraint', '{{cars_users}}');
        $this->dropForeignKey('cars_users_cars_fk_constraint', '{{cars_users}}');
        $this->dropTable('{{%cars_users}}');
    }
}
