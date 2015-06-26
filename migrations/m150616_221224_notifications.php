<?php

use yii\db\Schema;
use yii\db\Migration;

class m150616_221224_notifications extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%notification}}', [
            'id' => Schema::TYPE_BIGINT .' UNSIGNED AUTO_INCREMENT ',
            'receiver' => Schema::TYPE_STRING .'(80) NOT NULL',
            'sender' => Schema::TYPE_STRING .'(80) NOT NULL',
            'message' => Schema::TYPE_TEXT . ' NOT NULL',
            'created_at' => Schema::TYPE_DATE . ' NOT NULL ',
            'email' => Schema::TYPE_BOOLEAN . ' NOT NULL ',
            'priority' => Schema::TYPE_STRING .'(40) NOT NULL',
            'PRIMARY KEY (id)',
            'KEY i_sender (sender)',
            'KEY i_receiver (receiver)',
        ], $tableOptions);
    }

    public function down()
    {
        $this->dropTable('{{%notification}}');

        return true;
    }
    
    /*
    // Use safeUp/safeDown to run migration code within a transaction
    public function safeUp()
    {
    }
    
    public function safeDown()
    {
    }
    */
}
