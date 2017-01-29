<?php

namespace App\Util;

class Config
{

    static $config;

    public function getConfig()
    {
        if (!isset(self::$config)) {
            self::$config = include dirname(__FILE__) . '/../../config.php';
        }
        return self::$config;
    }

    public function get($var)
    {
        return $this->getConfig()[$var];
    }
}
