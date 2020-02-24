<?php

namespace kvmanager\models;

use kvmanager\behaviors\ApolloBehavior;
use kvmanager\KVOldTrait;
use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "key_value".
 *
 * @property integer $key_value_id
 * @property string  $key_value_key
 * @property string  $key_value_value
 * @property string  $key_value_memo
 * @property string  $key_value_status
 * @property string  $key_value_create_at
 * @property string  $key_value_update_at
 */
class KeyValue extends BaseModel
{
    use KVOldTrait;

    public static $keyFieldName = 'key_value_key';
    public static $valueFieldName = 'key_value_value';
    public static $statusFieldName = 'key_value_status';

    public function behaviors()
    {
        return array_merge(parent::behaviors(), [
            // 同步到配置中心
            ApolloBehavior::class,
        ]);
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return ArrayHelper::getValue(Yii::$app->params, 'kvmanager.tableName', '{{%key_value}}');
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['key_value_key'], 'required'],
            [['key_value_key'], 'unique'],
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
            'key_value_id'        => Yii::t('kvmanager', 'ID'),
            'key_value_key'       => Yii::t('kvmanager', 'Key'),
            'key_value_value'     => Yii::t('kvmanager', 'Value'),
            'key_value_memo'      => Yii::t('kvmanager', 'Memo'),
            'key_value_status'    => Yii::t('kvmanager', 'Status'),
            'key_value_create_at' => Yii::t('kvmanager', 'Created At'),
            'key_value_update_at' => Yii::t('kvmanager', 'Updated At'),
        ];
    }

    /**
     * 获取格式化后的值
     *
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
