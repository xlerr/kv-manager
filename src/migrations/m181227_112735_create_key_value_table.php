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
    `id`         int auto_increment primary key,
    `namespace`  varchar(64) default 'portal'          not null comment '命名空间',
    `group`      varchar(64) default 'default'         not null comment '分组',
    `type`       varchar(16) default 'json'            not null comment '类型',
    `key`        varchar(100)                          not null comment '键',
    `value`      text                                  null comment '值',
    `memo`       text                                  null comment '备注',
    `created_at` timestamp   default CURRENT_TIMESTAMP not null comment '创建时间',
    `updated_at` timestamp   default CURRENT_TIMESTAMP not null on update CURRENT_TIMESTAMP comment '更新时间',
    `updated_by` int         default 0                 not null comment '修改人',
    `created_by` int         default 0                 not null comment '创建人',
    constraint uk_namespace_group_key unique (`namespace`, `group`, `key`)
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
