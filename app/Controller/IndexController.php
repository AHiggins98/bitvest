<?php
namespace App\Controller;

use App\Util\View;
use App\Controller\ViewController;

class IndexController extends ViewController
{
  public function indexAction(array $unfilteredRequestParams)
  {
    $this->view->addVars($unfilteredRequestParams);
    $this->view->render('index');
  }
}
