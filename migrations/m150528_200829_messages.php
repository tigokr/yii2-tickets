<?php

use yii\db\Schema;
use yii\db\Migration;

class m150528_200829_messages extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%message}}', [
            'id' => Schema::TYPE_BIGINT .' UNSIGNED AUTO_INCREMENT ',
            'thread' => Schema::TYPE_BIGINT .' UNSIGNED ',
            'parent_id' => Schema::TYPE_BIGINT .' UNSIGNED NULL DEFAULT NULL ',
            'author_id'=>Schema::TYPE_BIGINT.' UNSIGNED COMMENT "Автор"',
            'type' => Schema::TYPE_SMALLINT .' UNSIGNED NOT NULL',
            'anon' => Schema::TYPE_SMALLINT .' UNSIGNED NOT NULL',
            'text' => Schema::TYPE_TEXT .' NOT NULL',
            'file' => Schema::TYPE_TEXT ,
            'created_at' => Schema::TYPE_TIMESTAMP . ' NOT NULL',
            'status' => Schema::TYPE_SMALLINT .' UNSIGNED NOT NULL',

            'PRIMARY KEY (id)',
        ], $tableOptions);

        $this->addForeignKey('message_fk_user', '{{%message}}', 'author_id', '{{%user}}', 'id', 'SET NULL');
    }

    public function down()
    {
        $this->dropForeignKey('message_fk_user', '{{%message}}');
        $this->dropTable('{{%message}}');

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
