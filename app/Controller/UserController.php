<?php

namespace App\Controller;

use App\Util\Config;
use App\Util\View;
use App\Util\Session;
use App\Util\HeaderParams;
use App\Model\Auth;
use App\Model\Form\Login;
use App\Model\Menu;
use App\Model\Form\Signup;
use App\Model\Users;

class UserController extends ViewController
{
    private $session;
    private $headers;
    private $auth;
    private $loginForm;
    private $signupForm;
    private $users;
    
    public function __construct(Config $config, 
            View $view, Menu $menu, Session $session, 
            HeaderParams $headers, Auth $auth, Login $loginForm, Signup $signupForm,
            Users $users)
    {
        parent::__construct($config, $view, $menu);
        $this->session = $session;
        $this->headers = $headers;
        $this->auth = $auth;
        $this->loginForm = $loginForm;
        $this->signupForm = $signupForm;
        $this->users = $users;
    }

    public function loginAction(array $unfilteredRequestParams)
    {
        $this->view->addVars($unfilteredRequestParams);
        $vars = $this->loginForm->getVars();
        $this->view->addVars($vars);
        $this->view->render('login');
    }
    
    public function loginSubmitAction(array $p)
    {
        $this->session->regenerate();
            
        $login = $this->loginForm->validate($p);
        
        if ($login['hasErrors']) {
            $this->session->set('loggedIn', false);
            $this->session->set('formErrors', $login['formErrors']);
            $this->headers->redirect('user/login');
            return;
        }
        
        $this->successfulLogin($login['formValues']['email']);
    }
    
    public function signupAction(array $unfilteredRequestParams)
    {
        $this->view->addVars($unfilteredRequestParams);
        
        $vars = $this->signupForm->getVars();
        $this->view->addVars($vars);
        
        $this->view->render('signup');
    }
    
    public function signupSubmitAction(array $unfilteredRequestParams)
    {
        $signup = $this->signupForm->validate($unfilteredRequestParams);
        
        if ($signup['hasErrors']) {
            $this->session->set('formErrors', $signup['formErrors']);
            $this->headers->redirect('user/signup');
            return;
        }
        
        $this->users->add($signup['formValues']['email'], $signup['formValues']['password']);
        
        $this->session->set('message', 'Confirmation email has been sent. Please check your email to login.');
        $this->headers->redirect('');
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
    
    public function verifyAction(array $p)
    {
        // TODO: Verify
        $this->successfulLogin($p['email']);
    }
    
    private function successfulLogin($email)
    {
        $this->session->set('message', 'Successfully logged in as ' . $email);
        $this->session->set('loggedIn', true);
        $this->session->set('email', $email);
        $this->headers->redirect('');
    }
}
