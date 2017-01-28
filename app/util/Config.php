<?php
namespace App\Util;

class Config
{
  static $config;

  public function getConfig()
  {
    if (!isset(self::$config)) {
      self::$config = include '../app/config.php';
    }
    return self::$config;
  }
}
