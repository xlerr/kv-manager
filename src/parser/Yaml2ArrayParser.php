<?php

namespace kvmanager\parser;

use Symfony\Component\Yaml\Yaml;

class Yaml2ArrayParser extends Parser
{
    /**
     * @param string $raw
     *
     * @return array
     */
    public function parse(string $raw)
    {
        return Yaml::parse($raw);
    }
}
