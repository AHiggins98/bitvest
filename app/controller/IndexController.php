<?php
namespace App\Controller;

require_once '../app/util/View.php';

use App\Util\View;

class IndexController
{
  private $view;

  public function __construct()
  {
    $this->view = new View();
  }

  public function indexAction(array $unfilteredRequestParams)
  {
    $this->view->render('index');
  }
}
