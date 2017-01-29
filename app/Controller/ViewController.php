<?php
namespace App\Controller;

use App\Util\View;
use App\Util\Config;
use App\Model\Menu;

abstract class ViewController extends BaseController
{
  protected $view;
  protected $menu;

  public function __construct(Config $config, View $view, Menu $menu)
  {
    parent::__construct($config);
    $this->view = $view;
    $this->view->addVars($this->config->getConfig());
    $this->menu = $menu;
    $this->view->addVars(['menu' => $menu]);
  }
}

