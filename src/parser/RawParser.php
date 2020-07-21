<?php

namespace kvmanager\parser;

class RawParser extends BaseParser
{
    /**
     * @param string $raw
     *
     * @return string
     */
    public function parse(string $raw)
    {
        return $raw;
    }
}
