<?php

namespace kvmanager\models;

use kemanager\NacosApiException;
use kvmanager\behaviors\NacosBehavior;
use kvmanager\components\NacosComponent;
use kvmanager\KVException;
use xlerr\CodeEditor\CodeEditor;
use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "key_value".
 *
 * @property integer $key_value_id
 * @property string  $key_value_namespace
 * @property string  $key_value_group
 * @property string  $key_value_key
 * @property string  $key_value_type
 * @property string  $key_value_value
 * @property string  $key_value_memo
 * @property string  $key_value_create_at
 * @property string  $key_value_update_at
 */
class KeyValue extends BaseModel
{
    public static $namespaceFieldName = 'key_value_namespace';
    public static $groupFieldName = 'key_value_group';
    public static $keyFieldName = 'key_value_key';
    public static $typeFieldName = 'key_value_type';
    public static $valueFieldName = 'key_value_value';

    public function behaviors()
    {
        return array_merge(parent::behaviors(), [
            // 同步到配置中心
            NacosBehavior::class,
        ]);
    }

    public static function typeList()
    {
        try {
            $config = self::take(NacosComponent::CONFIG_KEY);
        } catch (KVException $e) {
        }

        return [
                'text' => 'TEXT',
                'json' => 'JSON',
            ] + ($config['types'] ?? []);
    }

    public static function getEditorModes()
    {
        try {
            $config = self::take(NacosComponent::CONFIG_KEY);
        } catch (KVException $e) {
        }

        return [
                'text' => CodeEditor::MODE_Text,
                'json' => CodeEditor::MODE_JSON,
            ] + ($config['modes'] ?? []);
    }

    public function getEditorMode()
    {
        $mapping = self::getEditorModes();
        if (isset($mapping[$this->key_value_type])) {
            return $mapping[$this->key_value_type];
        }

        return CodeEditor::MODE_Text;
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
            [[self::$namespaceFieldName], 'default', 'value' => self::getDefaultNamespace()],
            [[self::$groupFieldName], 'default', 'value' => self::getDefaultGroup()],
            [[self::$keyFieldName, self::$typeFieldName], 'required'],
            [[self::$typeFieldName], 'in', 'range' => array_keys(self::typeList())],
            [[self::$keyFieldName], 'string', 'max' => 100],
            [[self::$valueFieldName], 'string', 'max' => 20000],
            [
                [
                    'key_value_memo',
                    'key_value_create_at',
                    'key_value_update_at',
                ],
                'safe',
            ],
            [
                [self::$keyFieldName],
                'unique',
                'targetAttribute' => [self::$namespaceFieldName, self::$groupFieldName, self::$keyFieldName],
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'key_value_id'            => Yii::t('kvmanager', 'ID'),
            self::$namespaceFieldName => Yii::t('kvmanager', 'Namespace'),
            self::$groupFieldName     => Yii::t('kvmanager', 'Group'),
            self::$keyFieldName       => Yii::t('kvmanager', 'Key'),
            'key_value_type'          => Yii::t('kvmanager', 'Type'),
            self::$valueFieldName     => Yii::t('kvmanager', 'Value'),
            'key_value_memo'          => Yii::t('kvmanager', 'Memo'),
            'key_value_create_at'     => Yii::t('kvmanager', 'Created At'),
            'key_value_update_at'     => Yii::t('kvmanager', 'Updated At'),
        ];
    }

    /**
     * 同步
     *
     * @return int
     * @throws NacosApiException
     * @throws \yii\base\InvalidConfigException
     */
    public function pullConfig()
    {
        $instance = NacosComponent::instance();
        if (!$instance->pullConfig($this)) {
            throw new NacosApiException($instance->getError());
        }

        return self::updateAll([
            self::$valueFieldName => $instance->getData(),
        ], [
            'key_value_id' => $this->key_value_id,
        ]);
    }
}
