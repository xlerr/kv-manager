<?php

namespace kvmanager;

trait KVOldTrait
{
    /**
     * @param string $key
     * @param bool   $isThrowException
     *
     * @return array|object|string
     * @throws KVException
     */
    public static function getValue($key, $isThrowException = true)
    {
        try {
            return self::take($key, self::TAKE_FORMAT_OBJECT);
        } catch (KVException $e) {
            if ($isThrowException) {
                throw $e;
            }
        }
    }

    /**
     * @param string $key
     * @param bool   $isThrowException
     *
     * @return array|object|string
     * @throws KVException
     */
    public static function getValueAsArray($key, $isThrowException = true)
    {
        try {
            return self::take($key, self::TAKE_FORMAT_ARRAY);
        } catch (KVException $e) {
            if ($isThrowException) {
                throw $e;
            }
        }
    }
}
