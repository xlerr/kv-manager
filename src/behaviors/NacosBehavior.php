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
     * @param $event
     *
     * @throws KVException
     */
    public function releaseConfig($event)
    {
        /** @var BaseModel $model */
        $model = $this->owner;

        if ($model->{$model::$keyFieldName} === NacosComponent::CONFIG_KEY) {
            return;
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
