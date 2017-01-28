<?php
namespace App\Controller;

require_once '../app/util/View.php';
require_once '../app/controller/ViewController.php';

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
