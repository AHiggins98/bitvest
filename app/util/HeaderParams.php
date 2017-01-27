<?php

namespace App\Util;

class HeaderParams
{
  public function set($str)
  {
    header($str);
  }
}
