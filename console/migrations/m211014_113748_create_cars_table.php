<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%cars}}`.
 */
class m211014_113748_create_cars_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%cars}}', [
            'id' => $this->primaryKey(),

            'garage_id' => $this->integer()->notNull(),

            'model' => $this->string(32)->notNull(),
            'modification' => $this->string(32),
            'price' => $this->integer()->unsigned()->notNull(),

            'speed' => $this->integer()->unsigned()->notNull(),
            'mobility' => $this->integer()->unsigned()->notNull(),
            'brake' => $this->integer()->unsigned()->notNull(),

            'img_inactive' => $this->string(),
            'img_active' => $this->string(),
        ]);

        $this->addForeignKey('garages_fk_constraint', '{{cars}}',
            'garage_id', '{{garages}}', 'id', 'CASCADE');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('garages_fk_constraint', '{{cars}}');
        $this->dropTable('{{%cars}}');
    }
}
