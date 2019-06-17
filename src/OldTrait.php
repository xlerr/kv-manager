<?php

namespace kvmanager;

use Yii;
use yii\base\UserException;

trait OldTrait
{
    /**
     * @param $key
     * @return string
     */
    public static function getCacheKeyOld($key)
    {
        return 'KV_' . $key;
    }

    public static function getValue($key, $isThrowException = true)
    {
        $cacheKey = self::getCacheKeyOld($key);
        $value    = Yii::$app->cache->get($cacheKey);
        if (!$value) {
            $kv = self::findOne([
                'key_value_key'    => $key,
                'key_value_status' => 'active',
            ]);

            if (!$kv) {
                if ($isThrowException) {
                    throw new UserException(sprintf("Key[%s]不存在", $key));
                } else {
                    return null;
                }
            }

            Yii::$app->cache->set($cacheKey, $kv->key_value_value, 60 * 60 * 2);
            $jsonValue = json_decode($kv->key_value_value);

            return is_null($jsonValue) ? trim($kv->key_value_value) : $jsonValue;
        } else {
            $jsonValue = json_decode($value);

            return is_null($jsonValue) ? trim($value) : $jsonValue;
        }
    }

    public static function getValueAsArray($key, $isThrowException = true)
    {
        $cacheKey = self::getCacheKeyOld($key);
        $value    = Yii::$app->cache->get($cacheKey);
        if (!$value) {
            $kv = self::findOne([
                'key_value_key'    => $key,
                'key_value_status' => 'active',
            ]);

            if (!$kv) {
                if ($isThrowException) {
                    throw new UserException(sprintf("Key[%s]不存在", $key));
                } else {
                    return null;
                }
            }

            Yii::$app->cache->set($cacheKey, $kv->key_value_value, 60 * 60 * 2);
            $jsonValue = json_decode($kv->key_value_value, true);

            return is_null($jsonValue) ? trim($kv->key_value_value) : $jsonValue;
        } else {
            $jsonValue = json_decode($value, true);

            return is_null($jsonValue) ? trim($value) : $jsonValue;
        }
    }
}
