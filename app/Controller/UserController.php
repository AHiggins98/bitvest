<?php

namespace App\Controller;

use App\Util\Config;
use App\Util\View;
use App\Util\Session;
use App\Util\HeaderParams;
use App\Model\Auth;
use App\Model\Form\Login;
use App\Model\Menu;

class UserController extends ViewController
{
    private $session;
    private $headers;
    private $auth;
    private $loginForm;
    
    public function __construct(Config $config, 
            View $view, Menu $menu, Session $session, 
            HeaderParams $headers, Auth $auth, Login $loginForm)
    {
        parent::__construct($config, $view, $menu);
        $this->session = $session;
        $this->headers = $headers;
        $this->auth = $auth;
        $this->loginForm = $loginForm;
    }

    public function loginAction(array $unfilteredRequestParams)
    {
        if (isset($unfilteredRequestParams['password'])) {
            sleep(1);
        }
        $this->view->addVars($unfilteredRequestParams);
        $this->view->render('login', ['email' => 'a@b.com']);
    }
    
    public function loginSubmitAction(array $p)
    {
        $this->session->regenerate();
            
        $login = $this->loginForm->validate($p);
        
        if (!$login || !$this->auth->checkPassword($login['email'], $login['password'])) {
            $this->session->set('message', 'Invalid email/password');
            $this->session->set('loggedIn', false);
            $this->headers->redirect('user/login');
            return;
        }
        
        $this->session->set('message', 'Successfully logged in as ' . $login['email']);
        $this->session->set('loggedIn', true);
        $this->headers->redirect('');
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
    
    public function logoutAction()
    {
        $this->session->end();
        $this->headers->redirect('');
    }
            
}
