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
        $sql = <<<SQL
create table key_value
(
    key_value_id        int auto_increment
        primary key,
    key_value_namespace varchar(64) default 'portal'          not null comment '命名空间',
    key_value_group     varchar(64) default 'default'         not null comment '分组',
    key_value_key       varchar(100)                          not null comment '键',
    key_value_type      varchar(16) default 'json'            not null comment '类型',
    key_value_value     text                                  null comment '值',
    key_value_memo      text                                  null comment '备注',
    key_value_create_at datetime    default CURRENT_TIMESTAMP not null comment '创建时间',
    key_value_update_at timestamp   default CURRENT_TIMESTAMP not null on update CURRENT_TIMESTAMP comment '更新时间',
    constraint uk_namespace_group_key
        unique (key_value_namespace, key_value_group, key_value_key)
);
SQL;

        $this->execute($sql);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('key_value');
    }
}
