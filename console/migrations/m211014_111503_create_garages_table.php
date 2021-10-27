<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%garages}}`.
 */
class m211014_111503_create_garages_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%garages}}', [
            'id' => $this->primaryKey(),

            'name' => $this->string(20)->notNull(),
            'description' => $this->string(),
//            'price' => $this->integer()->unsigned()->notNull(),

            'img_inactive' => $this->string(),
            'img_active' => $this->string(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%garages}}');
    }
}
