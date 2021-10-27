<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%users}}`.
 */
class m211012_121901_create_users_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%users}}', [
            'id' => $this->primaryKey(),
            'address' => $this->string()->notNull()->unique(),
            'rank' => $this->integer()->unsigned()->notNull()->unique(),
//            'username' => $this->string(16)->notNull()->unique(),
//            'password_hash' => $this->string(60)->notNull(),
//            'TRX' => $this->integer()->unsigned()->defaultValue(0),
//            'access_token_value' => $this->string(64),
//            'access_token_created_at' => $this->date(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%users}}');
    }
}
