<?php

namespace kvmanager\models;

use kvmanager\behaviors\CacheBehavior;
use kvmanager\KVException;
use Symfony\Component\Yaml\Yaml;
use Yii;
use yii\caching\Cache;
use yii\caching\TagDependency;
use yii\db\ActiveRecord;
use yii\di\Instance;

abstract class BaseModel extends ActiveRecord
{
    /**
     * @var string
     */
    public static $namespaceFieldName;

    /**
     * @var string
     */
    public static $groupFieldName;

    /**
     * @var string
     */
    public static $keyFieldName;

    /**
     * @var string
     */
    public static $typeFieldName;

    /**
     * @var string
     */
    public static $valueFieldName;

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
     * @return string
     */
    public static function getDefaultNamespace(): string
    {
        return Yii::$app->params['kvmanager']['defaultNamespace'] ?? 'portal';
    }

    /**
     * @return string
     */
    public static function getDefaultGroup(): string
    {
        return Yii::$app->params['kvmanager']['defaultGroup'] ?? 'default';
    }

    /**
     * @param array|string $key
     * @param string       $format
     *
     * @return array|object|string
     * @throws KVException
     */
    public static function take($key, $format = self::TAKE_FORMAT_ARRAY)
    {
        static $cache = [];
        [$namespace, $group, $key] = self::parseKey($key);
        if (isset($cache[static::class][$namespace][$group][$key])) {
            $config = $cache[static::class][$namespace][$group][$key];
        } else {
            $config = static::find()
                // 利用查询缓存
                ->cache(3600, new TagDependency([
                    'tags' => sprintf('%s:%s:%s:%s', static::class, $namespace, $group, $key),
                ]))
                ->where([
                    static::$namespaceFieldName => $namespace,
                    static::$groupFieldName     => $group,
                    static::$keyFieldName       => $key,
                ])
                ->select([
                    'value' => static::$valueFieldName,
                    'type'  => static::$typeFieldName,
                ])
                ->asArray()
                ->one();

            if (null === $config) {
                throw new KVException(sprintf('`%s.%s.%s` is not in \\%s', $namespace, $group, $key, static::class));
            }
            $cache[static::class][$namespace][$group][$key] = $config;
        }

        if ($format === self::TAKE_FORMAT_RAW) {
            return $config['value'];
        }

        if ($config['type'] === 'json') {
            $config['value'] = json_decode($config['value'], true);
        } elseif ($config['type'] === 'yaml') {
            $config['value'] = Yaml::parse($config['value']);
        } else {
            throw new KVException(sprintf('不支持解析[%s]格式内容', $config['type']));
        }

        if ($format === self::TAKE_FORMAT_ARRAY) {
            return (array)$config['value'];
        } elseif ($format === self::TAKE_FORMAT_OBJECT) {
            return (object)$config['value'];
        }

        throw new KVException('返回值格式异常: ' . $format);
    }

    /**
     * @param array|string $key
     *
     * @return array [namespace, group, key]
     */
    protected static function parseKey($key)
    {
        if (is_string($key)) {
            $key = explode('.', $key, 3);
        }

        switch (count($key)) {
            case 1:
                array_unshift($key, self::getDefaultGroup());
            case 2;
                array_unshift($key, self::getDefaultNamespace());
        }

        return $key;
    }

    /**
     * @throws \yii\base\InvalidConfigException
     */
    public function cleanCache()
    {
        /** @var Cache $cache */
        $cache = Instance::ensure(static::getDb()->queryCache, Cache::class);

        TagDependency::invalidate($cache, [
            vsprintf('%s:%s:%s:%s', [
                get_class($this),
                $this->getAttribute(static::$namespaceFieldName),
                $this->getAttribute(static::$groupFieldName),
                $this->getAttribute(static::$keyFieldName),
            ]),
        ]);
    }
}
