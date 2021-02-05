<?php

namespace kvmanager\parser;

use kvmanager\KVException;

abstract class Parser
{
    public static $parser = [
        'text2raw'    => RawParser::class,
        'json2raw'    => RawParser::class,
        'json2array'  => Json2ArrayParser::class,
        'json2object' => Json2ObjectParser::class,
        'yaml2raw'    => RawParser::class,
        'yaml2array'  => Yaml2ArrayParser::class,
        'yaml2object' => Yaml2ObjectParser::class,
    ];

    /**
     * @param string $source
     * @param string $format
     *
     * @return Parser
     * @throws KVException
     */
    public static function create(string $source, string $format)
    {
        $parserName = sprintf('%s2%s', $source, $format);

        if (!isset(self::$parser[$parserName])) {
            throw new KVException(sprintf('`%s`类型配置不能解析为`%s`格式', $source, $format));
        }

        return new self::$parser[$parserName]();
    }

    /**
     * @param string $raw
     *
     * @return mixed
     */
    abstract public function parse(string $raw);
}
