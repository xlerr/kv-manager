<?php

namespace kvmanager;

use Exception;

class KVException extends Exception
{
    /**
     * @return string the user-friendly name of this exception
     */
    public function getName()
    {
        return 'KV Exception';
    }
}