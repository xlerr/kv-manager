<?php

namespace kvmanager\models;

use kvmanager\behaviors\ApolloBehavior;
use kvmanager\behaviors\KeyValueCacheBehavior;
use kvmanager\ConfigTrait;
use kvmanager\OldTrait;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "key_value".
 * @property integer $key_value_id
 * @property string $key_value_key
 * @property string $key_value_value
 * @property string $key_value_memo
 * @property string $key_value_status
 * @property string $key_value_create_at
 * @property string $key_value_update_at
 */
class KeyValue extends ActiveRecord
{
    use ConfigTrait;
    use OldTrait;

    const STATUS_ACTIVE = 'active';
    const STATUS_INACTIVE = 'inactive';
    const STATUS_LIST = [
        self::STATUS_ACTIVE   => '激活',
        self::STATUS_INACTIVE => '未激活',
    ];

    public function transactions()
    {
        return [
            self::SCENARIO_DEFAULT => self::OP_ALL,
        ];
    }

    public function behaviors()
    {
        return [
            // 同步到配置中心
            ApolloBehavior::class,

            // 缓存
            KeyValueCacheBehavior::class,
        ];
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'key_value';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['key_value_key'], 'required'],
            [['key_value_key'], 'unique', 'message' => '{attribute}`{value}`已存在!'],
            [['key_value_key'], 'string', 'max' => 100],
            [['key_value_value'], 'string', 'max' => 20000],
            [['key_value_status', 'key_value_memo'], 'string'],
            [['key_value_create_at', 'key_value_update_at'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'key_value_id'        => '主键',
            'key_value_key'       => '键',
            'key_value_value'     => '值',
            'key_value_memo'      => '备注',
            'key_value_status'    => '状态',
            'key_value_create_at' => '创建时间',
            'key_value_update_at' => '更新时间',
        ];
    }

    /**
     * 获取格式化后的值
     * @return string
     */
    public function getFormattedValue()
    {
        $data = json_decode($this->key_value_value, true);
        if (null === $data || !is_array($data)) {
            // 兼容值为无效json的数据
            return $this->key_value_value;
        } else {
            return json_encode($data, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
        }
    }
}
