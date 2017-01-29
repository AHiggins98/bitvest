<?php
namespace App\Controller;

use App\Util\View;
use App\Controller\ViewController;
use App\Util\Config;
use App\Util\Session;
use App\Model\Menu;

class IndexController extends ViewController
{
    private $session;
    
    public function __construct(Config $config, View $view, Menu $menu, Session $session)
    {
        parent::__construct($config, $view, $menu);
        $this->session = $session;
    }
    
  public function indexAction(array $unfilteredRequestParams)
  {
    $this->view->addVars($unfilteredRequestParams);
    
    $message = $this->session->get('message');
    $this->session->set('message', null);
    $vars = [
        'message' => $message,
        'loggedIn' => $this->session->get('loggedIn'),
    ];
    $this->view->render('index', $vars);
  }
  
  public function errorAction($p)
  {
      $this->view->addVars($p);
      $this->view->render('error');
  }
}
