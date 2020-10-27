<?php

namespace kvmanager\models;

use common\models\User;
use kvmanager\behaviors\NacosBehavior;
use kvmanager\components\NacosComponent;
use kvmanager\KVException;
use kvmanager\NacosApiException;
use xlerr\CodeEditor\CodeEditor;
use Yii;
use yii\base\InvalidConfigException;
use yii\behaviors\BlameableBehavior;
use yii\helpers\ArrayHelper;
use yii\web\ForbiddenHttpException;

/**
 * This is the model class for table "key_value".
 *
 * @property int       $id
 * @property string    $namespace
 * @property string    $group
 * @property string    $key
 * @property string    $type
 * @property string    $value
 * @property string    $memo
 * @property string    $created_at
 * @property string    $updated_at
 * @property int       $updated_by
 * @property int       $created_by
 * @property-read User $creator
 * @property-read User $operator
 */
class KeyValue extends BaseModel
{
    const TYPE_TEXT = 'text';
    const TYPE_JSON = 'json';
    const TYPE_YAML = 'yaml';

    public static $namespaceFieldName = 'namespace';
    public static $groupFieldName = 'group';
    public static $keyFieldName = 'key';
    public static $typeFieldName = 'type';
    public static $valueFieldName = 'value';

    public function behaviors()
    {
        return array_merge(parent::behaviors(), [
            BlameableBehavior::class,
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
                self::TYPE_TEXT => 'TEXT',
                self::TYPE_JSON => 'JSON',
                self::TYPE_YAML => 'YAML',
            ] + ((array)($config['types'] ?? []));
    }

    public static function getEditorModes()
    {
        try {
            $config = self::take(NacosComponent::CONFIG_KEY);
        } catch (KVException $e) {
        }

        return [
                self::TYPE_TEXT => CodeEditor::MODE_Text,
                self::TYPE_JSON => CodeEditor::MODE_JSON,
                self::TYPE_YAML => CodeEditor::MODE_YAML,
            ] + ((array)($config['modes'] ?? []));
    }

    public function getEditorMode()
    {
        $mapping = self::getEditorModes();
        if (isset($mapping[$this->type])) {
            return $mapping[$this->type];
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
     * @param $namespace
     * @param $group
     *
     * @return bool
     * @throws ForbiddenHttpException
     */
    public static function permissionCheck($namespace, $group)
    {
        $config = self::getAvailable();

        return $namespace && $group && isset($config[$namespace]) && in_array($group, $config[$namespace], true);
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
            [[self::$namespaceFieldName, self::$groupFieldName], 'string', 'max' => 64],
            [[self::$valueFieldName], 'string', 'max' => 20000],
            [
                [
                    'memo',
                    'created_at',
                    'updated_at',
                    'created_by',
                    'updated_by',
                ],
                'safe',
            ],
            [
                [self::$keyFieldName],
                'unique',
                'targetAttribute' => [
                    self::$namespaceFieldName,
                    self::$groupFieldName,
                    self::$keyFieldName,
                ],
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id'                      => Yii::t('kvmanager', 'ID'),
            self::$namespaceFieldName => Yii::t('kvmanager', 'Namespace'),
            self::$groupFieldName     => Yii::t('kvmanager', 'Group'),
            self::$keyFieldName       => Yii::t('kvmanager', 'Key'),
            self::$valueFieldName     => Yii::t('kvmanager', 'Value'),
            self::$typeFieldName      => Yii::t('kvmanager', 'Type'),
            'memo'                    => Yii::t('kvmanager', 'Memo'),
            'created_at'              => Yii::t('kvmanager', 'Created At'),
            'updated_at'              => Yii::t('kvmanager', 'Updated At'),
            'updated_by'              => Yii::t('kvmanager', 'Updated By'),
            'created_by'              => Yii::t('kvmanager', 'Created By'),
        ];
    }

    public function getCreator()
    {
        return $this->hasOne(Yii::$app->getUser()->identityClass, ['id' => 'created_by']);
    }

    public function getOperator()
    {
        return $this->hasOne(Yii::$app->getUser()->identityClass, ['id' => 'updated_by']);
    }

    /**
     * 同步
     *
     * @return int
     * @throws NacosApiException
     * @throws InvalidConfigException
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
            'id' => $this->id,
        ]);
    }
}
