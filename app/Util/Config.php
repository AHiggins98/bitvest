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
        if (!isset($this->getConfig()[$var])) {
            throw new \Exception("Config variable `$var` must be defined in config.php");
        }
        return $this->getConfig()[$var];
    }
}
