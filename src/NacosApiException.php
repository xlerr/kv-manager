<?php

namespace kemanager;

use Exception;

class NacosApiException extends Exception
{
    /**
     * @return string the user-friendly name of this exception
     */
    public function getName()
    {
        return 'Nacos Api Exception';
    }
}
