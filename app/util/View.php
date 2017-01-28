<?php
namespace App\Util;

class View
{
  public function render($view, $vars = [])
  {
    include '../app/view/' . $view . '.phtml';
  }
}
