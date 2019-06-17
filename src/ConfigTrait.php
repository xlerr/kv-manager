<?php

namespace kvmanager;

use kvmanager\models\KeyValue;
use Yii;

trait ConfigTrait
{
    /**
     * @param $key
     * @return string
     */
    public static function getCacheKey($key)
    {
        return 'KV__' . $key;
    }

    /**
     * @param $key
     * @return Config
     * @throws ConfigException
     */
    public static function take($key)
    {
        $cache    = Yii::$app->getCache();
        $cacheKey = self::getCacheKey($key);
        $value    = $cache->get($cacheKey);
        if (false === $value) {
            $model = KeyValue::findOne([
                'key_value_key'    => $key,
                'key_value_status' => KeyValue::STATUS_ACTIVE,
            ]);
            if (!$model) {
                throw new ConfigException(vsprintf('%s does not exist in [%s].', [
                    $key,
                    'KeyValue',
                ]));
            }

            $value = json_decode($model->key_value_value, true);
            if (null === $value) {
                $value = $model->key_value_value;
            }

            $cache->set($cacheKey, $value, 7200);
        }

        return new Config($value, $key);
    }
}
