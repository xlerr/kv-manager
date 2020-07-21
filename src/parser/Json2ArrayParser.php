<?php

namespace kvmanager\parser;

use kvmanager\KVException;

class Json2ArrayParser extends BaseParser
{
    /**
     * @param string $raw
     *
     * @return array
     * @throws KVException
     */
    public function parse(string $raw)
    {
        $config = json_decode($raw, true);
        if (null === $config) {
            throw new KVException('无效的JSON: ' . json_last_error_msg());
        }

        return $config;
    }
}
