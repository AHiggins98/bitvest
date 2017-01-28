<?php
namespace App\Controller;

require_once '../app/util/Config.php';

use App\Util\Config;

class BaseController
{
  protected $config;

  public function __construct()
  {
    $this->config = new Config();
  }
}
