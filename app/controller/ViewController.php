<?php
namespace App\Controller;

require_once '../app/util/View.php';
require_once '../app/controller/BaseController.php';

use App\Util\View;

abstract class ViewController extends BaseController
{
  protected $view;

  public function __construct()
  {
    parent::__construct();
    $this->view = new View();
    $this->view->addVars($this->config->getConfig());
  }
}

