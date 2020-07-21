<?php

namespace kvmanager\parser;

use stdClass;
use Symfony\Component\Yaml\Yaml;

class Yaml2ObjectParser extends Parser
{
    /**
     * @param string $raw
     *
     * @return stdClass
     */
    public function parse(string $raw)
    {
        return Yaml::parse($raw, Yaml::PARSE_OBJECT_FOR_MAP);
    }
}
