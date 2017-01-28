<?php

namespace App\Util;

class HeaderParams
{
  public function set($str)
  {
    header($str);
  }

  public function setResponseCode($code)
  {
    http_response_code($code);
  }
}
