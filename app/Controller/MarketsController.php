<?php

namespace App\Controller;

use App\Util\View;
use App\Util\Config;
use App\Model\Menu;

class MarketsController extends ViewController
{
    public function __construct(Config $config, View $view, Menu $menu)
    {
        parent::__construct($config, $view, $menu);
    }
    
    public function indexAction(array $p)
    {
        $this->view->addVars($p);
        $this->view->render('markets/index');
    }
}