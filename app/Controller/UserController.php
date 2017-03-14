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
use App\Util\Di;
use App\Util\Validator;

class UserController extends ViewController
{
    private $session;
    private $headers;
    private $auth;
    private $loginForm;
    private $signupForm;
    private $validator;
    private $users;
    private $di;
    
    public function __construct(Config $config,
            View $view, Menu $menu, Session $session,
            HeaderParams $headers, Auth $auth, Login $loginForm, Signup $signupForm,
            Users $users, Validator $validator, Di $di)
    {
        parent::__construct($config, $view, $menu);
        $this->session = $session;
        $this->headers = $headers;
        $this->auth = $auth;
        $this->loginForm = $loginForm;
        $this->signupForm = $signupForm;
        $this->users = $users;
        $this->validator = $validator;
        $this->di = $di;
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
        
        $user = $this->createUserObject();
        $user->email = $this->signupForm->getValue('email');
        $user->password = $this->signupForm->getValue('password');
        
        // Add user to database
        $this->users->add($user);
        
        // Send confirmation email
        $user->sendConfirmationEmail();
        
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
        
        $user = $this->createUserObject();
        $user->id = $this->session->get('userId');
        $this->users->loadUser($user);
        
        $this->view->addVars($vars);
        $this->view->render('user/account');
    }
    
    public function logoutAction()
    {
        $this->session->end();
        $this->session->set('message', 'Logout successful');
        $this->headers->redirect('');
    }
    
    public function verifyAction(array $p)
    {
        if (!$this->validator->isValidEmailString($p['email']) ||
            !$this->validator->isValidVerifyCodeString($p['verifyCode'])) {
            throw new \Exception('Invalid input');
        }
        
        if (!$this->users->emailExists($p['email'])) {
            throw new \Exception('Invalid email specified');
        }
        
        $user = $this->createUserObject();
        $user->email = $p['email'];
        $this->users->loadUser($user);
        
        if ($user->verifyCode == $p['verifyCode']) {
            // Clear the verifyCode for the user and save it
            $user->verifyCode = null;
            $this->users->saveUser($user);
        } else {
            throw new \Exception('Invalid verifyCode specified');
        }
        
        $this->successfulLogin($p['email']);
    }
    
    private function successfulLogin($email)
    {
        $user = $this->createUserObject();
        $user->email = $email;
        $this->users->loadUser($user);
        
        if (isset($user->verifyCode)) {
            $this->headers->redirect('user/needs-verification');
            $this->session->set('userId', $user->id);
            return;
        }
        
        $this->session->set('message', 'Successfully logged in as ' . $email);
        $this->session->set('loggedIn', true);
        $this->session->set('userId', $user->id);
        $this->headers->redirect('user/account');
    }
    
    public function needsVerificationAction(array $p)
    {
        $this->view->addVars($p);
        $this->view->render('user/needs-verification');
    }
    
    public function resendVerificationAction(array $p)
    {
        $user = $this->createUserObject();
        $user->id = $this->session->get('userId');
        
        $this->users->loadUser($user);
        
        // Send confirmation email
        $user->sendConfirmationEmail();
        
        $this->session->set('message',
                'Confirmation email has been sent. Please check your email to login.');
        
        $this->headers->redirect('');
    }
    
    /**
     * Create a new User object.
     *
     * @return User
     */
    private function createUserObject()
    {
        return $this->di->create(User::class);
    }
}
