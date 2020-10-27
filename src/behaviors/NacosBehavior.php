<?php

namespace kvmanager\behaviors;

use kvmanager\components\NacosComponent;
use kvmanager\KVException;
use kvmanager\models\BaseModel;
use kvmanager\models\KeyValue;
use yii\base\Behavior;
use yii\db\ActiveRecord;

/**
 * 同步到Nacos
 */
class NacosBehavior extends Behavior
{
    public function events()
    {
        return [
            ActiveRecord::EVENT_AFTER_INSERT => [$this, 'releaseConfig'],
            ActiveRecord::EVENT_AFTER_UPDATE => [$this, 'releaseConfig'],
            ActiveRecord::EVENT_AFTER_DELETE => [$this, 'deleteConfig'],
        ];
    }

    /**
     * @return array
     * @throws KVException
     */
    public function getExceptSyncConfig()
    {
        $config = KeyValue::take(NacosComponent::CONFIG_KEY);

        return (array)($config['exceptSync'] ?? []);
    }

    /**
     * @param $event
     *
     * @throws KVException
     */
    public function releaseConfig($event)
    {
        /** @var BaseModel $model */
        $model = $this->owner;

        $fullkey = vsprintf('%s.%s.%s', [
            $model->{$model::$namespaceFieldName},
            $model->{$model::$groupFieldName},
            $model->{$model::$keyFieldName},
        ]);

        $exceptSyncList = $this->getExceptSyncConfig();
        foreach ($exceptSyncList as $except) {
            if (stripos($fullkey, $except) !== false) {
                return;
            }
        }

        $nacos = NacosComponent::instance();
        if (!$nacos->releaseConfig($model)) {
            throw new KVException($nacos->getError());
        }
    }

    /**
     * @param $event
     *
     * @throws KVException
     */
    public function deleteConfig($event)
    {
        /** @var KeyValue $model */
        $model = $this->owner;

        if ($model->{$model::$keyFieldName} === NacosComponent::CONFIG_KEY) {
            return;
        }

        $nacos = NacosComponent::instance();
        if (!$nacos->deleteConfig($model)) {
            throw new KVException($nacos->getError());
        }
    }
}
