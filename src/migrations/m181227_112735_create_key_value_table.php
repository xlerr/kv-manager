<?php

use yii\db\Migration;

/**
 * Handles the creation of table `key_value`.
 */
class m181227_112735_create_key_value_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('key_value', [
            'key_value_id' => $this->primaryKey(),
            'key_value_key' => $this->string(100)->notNull()->comment('键'),
            'key_value_value' => $this->text()->comment('值'),
            'key_value_memo' => $this->text()->comment('备注'),
            'key_value_status' => "enum('active','inactive') NOT NULL DEFAULT 'active' COMMENT '状态：active-激活，inactive-未激活'",
            'key_value_create_at' => "datetime NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间'",
            'key_value_update_at' => "timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '更新时间'",
        ]);

        $this->createIndex('i_key_value_key_key_value_status', 'key_value', ['key_value_key', 'key_value_status']);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('key_value');
    }
}
