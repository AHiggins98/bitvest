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
use App\Model\User;

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
        
        $loginFormState = $this->session->getOnce('loginFormState');
        
        if (isset($loginFormState)) {
            $this->view->addVars($loginFormState);
        } else {
            $this->view->addVars($this->loginForm->getState());
        }
        
        $this->view->render('user/login');
    }
    
    public function loginSubmitAction(array $p)
    {
        $this->session->regenerate();
            
        $this->loginForm->validate($p);
        
        if ($this->loginForm->hasErrors()) {
            $this->session->set('loggedIn', false);
            $this->session->set('loginFormState', $this->loginForm->getState());
            $this->headers->redirect('user/login');
            return;
        }
        
        $this->successfulLogin($this->loginForm->getValue('email'));
    }
    
    public function signupAction(array $unfilteredRequestParams)
    {
        $this->view->addVars($unfilteredRequestParams);
        
        $signupFormState = $this->session->getOnce('signupFormState');
        
        if (isset($signupFormState)) {
            $this->view->addVars($signupFormState);
        } else {
            $this->view->addVars($this->signupForm->getState());
        }
        
        $this->view->render('user/signup');
    }
    
    public function signupSubmitAction(array $unfilteredRequestParams)
    {
        $this->signupForm->validate($unfilteredRequestParams);
        
        if ($this->signupForm->hasErrors()) {
            $this->session->set('signupFormState', $this->signupForm->getState());
            $this->headers->redirect('user/signup');
            return;
        }
        
        // Add user and send confirmation email
        $user = new User();
        $user->email = $this->signupForm->getValue('email');
        $user->password = $this->signupForm->getValue('password');
        
        $this->users->add($user);
        
        $this->session->set('message',
                'Confirmation email has been sent. Please check your email to login.');
        
        $this->headers->redirect('');
    }
    
    public function accountAction(array $params)
    {
        $vars = [
            'message' => $this->session->getOnce('message'),
        ];
        
        $vars += $params;
        
        $user = new User();
        $user->id = $this->session->get('userId');
        $this->users->loadUser($user);
        
        $this->view->addVars($vars);
        $this->view->render('user/account');
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
        $user = new User();
        $user->email = $email;
        $this->users->loadUser($user);
        $this->session->set('message', 'Successfully logged in as ' . $email);
        $this->session->set('loggedIn', true);
        $this->session->set('userId', $user->id);
        $this->headers->redirect('user/account');
    }
}
