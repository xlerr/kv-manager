<?php

namespace kvmanager\models;

use kvmanager\behaviors\CacheBehavior;
use kvmanager\KVException;
use kvmanager\parser\Parser;
use stdClass;
use Yii;
use yii\base\InvalidConfigException;
use yii\caching\Cache;
use yii\db\ActiveRecord;

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
     * @param string      $key
     * @param null|string $namespace
     * @param null|string $group
     *
     * @return stdClass
     * @throws KVException
     */
    public static function takeAsObject($key, $namespace = null, $group = null)
    {
        return self::take($key, $namespace, $group, self::TAKE_FORMAT_OBJECT);
    }

    /**
     * @param string      $key
     * @param null|string $namespace
     * @param null|string $group
     *
     * @return array
     * @throws KVException
     */
    public static function takeAsArray($key, $namespace = null, $group = null)
    {
        return self::take($key, $namespace, $group, self::TAKE_FORMAT_ARRAY);
    }

    /**
     * @param string      $key
     * @param null|string $namespace
     * @param null|string $group
     *
     * @return string
     * @throws KVException
     */
    public static function takeAsRaw($key, $namespace = null, $group = null)
    {
        return self::take($key, $namespace, $group, self::TAKE_FORMAT_RAW);
    }

    /**
     * @param string      $key
     * @param string|null $namespace
     * @param string|null $group
     * @param string      $format
     *
     * @return array|object|string
     * @throws KVException
     */
    public static function take($key, $namespace = null, $group = null, $format = self::TAKE_FORMAT_ARRAY)
    {
        $namespace = $namespace ?? self::getDefaultNamespace();
        $group     = $group ?? self::getDefaultGroup();

        $cache    = Yii::$app->getCache();
        $cacheKey = implode(':', [$namespace, $group, $key]);

        if (false === ($config = $cache->get($cacheKey))) {
            $config = static::find()
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
                throw new KVException(vsprintf('%s.%s.%s not found in \\%s', [
                    $namespace,
                    $group,
                    $key,
                    static::class,
                ]));
            }
            $cache->set($cacheKey, $config, 86400);
        }

        return Parser::create($config['type'], $format)->parse($config['value']);
    }

    /**
     * @throws InvalidConfigException
     */
    public function cleanCache()
    {
        /** @var Cache $cache */
        $cache = Yii::$app->getCache();

        $cacheKey = vsprintf('%s:%s:%s', [
            $this->getAttribute(static::$namespaceFieldName),
            $this->getAttribute(static::$groupFieldName),
            $this->getAttribute(static::$keyFieldName),
        ]);
        $cache->delete($cacheKey);
    }
}
