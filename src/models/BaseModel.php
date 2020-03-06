<?php

namespace kvmanager\models;

use kvmanager\behaviors\CacheBehavior;
use kvmanager\KVException;
use Yii;
use yii\base\Event;
use yii\base\InvalidConfigException;
use yii\caching\Cache;
use yii\caching\TagDependency;
use yii\db\ActiveRecord;
use yii\di\Instance;

abstract class BaseModel extends ActiveRecord
{
    /**
     * @var string
     */
    public static $keyFieldName;

    /**
     * @var string
     */
    public static $valueFieldName;

    /**
     * @var string
     */
    public static $statusFieldName;

    const STATUS_ACTIVE   = 'active';
    const STATUS_INACTIVE = 'inactive';

    const TAKE_FORMAT_ARRAY  = 'array';
    const TAKE_FORMAT_OBJECT = 'object';
    const TAKE_FORMAT_RAW    = 'raw';

    public function transactions()
    {
        return [
            self::SCENARIO_DEFAULT => self::OP_ALL,
        ];
    }

    public function behaviors()
    {
        return [
            CacheBehavior::class,
        ];
    }

    /**
     * @return array
     */
    public static function statusList()
    {
        return [
            self::STATUS_ACTIVE   => Yii::t('kvmanager', 'Active'),
            self::STATUS_INACTIVE => Yii::t('kvmanager', 'Inactive'),
        ];
    }

    /**
     * @param string $key
     * @param string $format
     *
     * @return array|object|string
     * @throws KVException
     */
    public static function take($key, $format = self::TAKE_FORMAT_ARRAY)
    {
        static $cache = [];
        if (isset($cache[static::class][$key])) {
            $val = $cache[static::class][$key];
        } else {
            $val = static::find()
                // 利用查询缓存
                ->cache(3600, new TagDependency([
                    'tags' => sprintf('%s:%s', static::class, $key),
                ]))
                ->where([
                    static::$keyFieldName    => $key,
                    static::$statusFieldName => static::STATUS_ACTIVE,
                ])
                ->select(static::$valueFieldName)
                ->scalar();

            if (false === $val) {
                throw new KVException(sprintf('`%s` is not in \\%s', $key, static::class));
            }
            $cache[static::class][$key] = $val;
        }
        if ($format === self::TAKE_FORMAT_ARRAY) {
            return (array) json_decode($val, true);
        } elseif ($format === self::TAKE_FORMAT_OBJECT) {
            return (object) json_decode($val);
        }

        return $val;
    }

    /**
     * @param Event $event
     *
     * @throws InvalidConfigException
     * @see CacheBehavior::events()
     */
    public function cleanCache(Event $event)
    {
        /** @var BaseModel $sender */
        $sender = $event->sender;

        /** @var Cache $cache */
        $cache = Instance::ensure($sender::getDb()->queryCache, Cache::class);

        TagDependency::invalidate($cache, [
            sprintf('%s:%s', get_class($sender), $sender->getAttribute($sender::$keyFieldName)),
        ]);
    }
}
