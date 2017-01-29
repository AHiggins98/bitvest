<?php

namespace App\Controller;

use App\Util\Config;
use App\Util\View;
use App\Util\Session;
use App\Util\HeaderParams;

class UserController extends ViewController
{
    private $session;
    private $headers;
    
    public function __construct(Config $config, View $view, Session $session, HeaderParams $headers)
    {
        parent::__construct($config, $view);
        $this->session = $session;
        $this->headers = $headers;
    }

    public function loginAction(array $unfilteredRequestParams)
    {
        if (isset($unfilteredRequestParams['password'])) {
            sleep(1);
        }
        $this->view->addVars($unfilteredRequestParams);
        $this->view->render('login', ['email' => 'a@b.com']);
    }
    
    public function signupAction(array $unfilteredRequestParams)
    {
        $this->view->addVars($unfilteredRequestParams);
        $this->view->render('signup');
    }
    
    public function signupSubmitAction(array $unfilteredRequestParams)
    {
        if (isset($unfilteredRequestParams['email']) && 
            isset($unfilteredRequestParams['password'])) {
            
            $email = $unfilteredRequestParams['email'];
            $password = $unfilteredRequestParams['password'];
            
            if (empty($email) || empty($password)) {
                $this->session->set('message', 'Email/password required.');
                $this->headers->redirect('/user/signup');
                return;
            }
            
            $this->view->addVars($unfilteredRequestParams);
            $this->view->render('confirm-sent');
            
        } else {
            $this->view->render('error');
        }
    }
    
    public function accountAction(array $params)
    {
        $this->view->addVars($params);
        $this->view->render('account');
    }

}
