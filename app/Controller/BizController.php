<?php

namespace App\Controller;

use App\Util\View;
use App\Util\Config;
use App\Model\Menu;
use App\Model\Form\CreateBusiness;
use App\Model\Businesses;
use App\Util\Session;
use App\Util\HeaderParams;


class BizController extends ViewController
{
    private $createBusinessForm;
    private $businesses;
    private $session;
    private $headers;

    public function __construct(Config $config, View $view, Menu $menu, CreateBusiness $createBusinessForm, Businesses $businesses, Session $session, HeaderParams $headers)
    {
        parent::__construct($config, $view, $menu);
        $this->createBusinessForm = $createBusinessForm;
        $this->businesses = $businesses;
        $this->session = $session;
        $this->headers = $headers;

    }
    
    public function startAction(array $p)
    {
        $this->view->addVars($p);
        
        $createBusinessFormState = $this->session->get('createBusinessFormState');
        
        if (isset($createBusinessFormState)) {
            $this->view->addVars($createBusinessFormState);
        } else {
            $this->view->addVars($this->createBusinessForm->getState());
        }
        
        $this->view->render('biz/start');
        
    }
    
    public function listAction(array $p)
    {
        $this->view->addVars($p);
        $this->view->render('biz/list');
    }

    public function addBusinessSubmitAction(array $p)
    {
        $this->createBusinessForm->validate($p);
        
        if ($this->createBusinessForm->hasErrors()) {
            $this->session->set('createBusinessFormState', $this->createBusinessForm->getState());
            $this->headers->redirect('biz/start');
            return;
        }
        
         // Add business and redirect to business list
        
        $this->businesses->add(
            $this->createBusinessForm->getValue('foundername'), 
            $this->createBusinessForm->getValue('businessname'),
            $this->createBusinessForm->getValue('shortname')
        );
        
        $businessname = $this->createBusinessForm->getValue('businessname');
        
        $this->session->set('message', 
                "Business <b>$businessname</b> added successfully.");
        
        $this->headers->redirect('');
    }
    
    
}
