<?php
namespace App\Util;

class Config
{
  static $config;

  public function getConfig()
  {
    if (!isset(self::$config)) {
      self::$config = include '../config.php';
    }
    return self::$config;
  }
}
