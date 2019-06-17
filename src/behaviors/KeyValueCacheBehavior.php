<?php

namespace kvmanager\behaviors;

use kvmanager\models\KeyValue;
use Yii;
use yii\base\Behavior;
use yii\db\ActiveRecord;

/**
 * 缓存控制
 */
class KeyValueCacheBehavior extends Behavior
{
    public function events()
    {
        return [
            ActiveRecord::EVENT_AFTER_UPDATE => [$this, 'cleanCache'],
            ActiveRecord::EVENT_AFTER_DELETE => [$this, 'cleanCache'],
        ];
    }

    /**
     * 清理缓存
     */
    public function cleanCache()
    {
        /** @var KeyValue $model */
        $model = $this->owner;

        $cache = Yii::$app->getCache();
        foreach (['getCacheKey', 'getCacheKeyOld'] as $method) {
            if (method_exists(KeyValue::class, $method)) {
                $cacheKey = call_user_func([KeyValue::class, $method], $model->key_value_key);
                $cache->delete($cacheKey);
            }
        }
    }
}
