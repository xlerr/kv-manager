<?php

namespace kvmanager\parser;

use kvmanager\KVException;
use stdClass;

class Json2ObjectParser extends Parser
{
    /**
     * @param string $raw
     *
     * @return stdClass
     * @throws KVException
     */
    public function parse(string $raw)
    {
        $config = json_decode($raw);
        if (null === $config) {
            throw new KVException('无效的JSON: ' . json_last_error_msg());
        }

        return $config;
    }
}
