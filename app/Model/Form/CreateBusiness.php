<?php

namespace App\Model\Form;

use App\Util\Validator;
use App\Model\Users;
use App\Util\Session;

class CreateBusiness extends AbstractForm
{
    private $validator;
    private $users;
    private $session;
    
    public function __construct(Validator $validator, Users $users, Session $session)
    {
        parent::__construct(['email', 'password']);
        $this->validator = $validator;
        $this->users = $users;
        $this->session = $session;
    }
    
    public function validate(array $params)
    {
        if (isset($params['email']) && isset($params['password'])) {

          // copied from formproc.phtml

    if (!isset($params['foundername']) ||
 
        !isset($params['businessname']) ||

        !isset($params['shortname'])) {
        died('Fields marked "*" are required!');
    }

 
            $foundername = $params['foundername'];
 
            $businessname = $params['businessname'];
 
            $shortname = $params['shortname'];
 
            $error_message = "";
 
            $email_exp = '/^[A-Za-z0-9._%-]+@[A-Za-z0-9.-]+\.[A-Za-z]{2,4}$/';
 
            if (!preg_match($email_exp, $email)) {
                $error_message .= 'The Email Address you entered does not appear to be valid.<br />';
            }
 
            $string_exp = "/^[A-Za-z .'-]+$/";
 
            if (!preg_match($string_exp, $foundername)) {
                $error_message .= 'The founder name you entered does not appear to be valid.<br />';
            }

            if (!preg_match($string_exp, $businessname)) {
                $error_message .= 'The business you entered does not appear to be valid.<br />';
            }

            if (!preg_match($string_exp, $shortname)) {
                $error_message .= 'The business you entered does not appear to be valid.<br />';
            }
 
 
            if (strlen($error_message) > 0) {
                died($error_message);
            }

  // end of copy

         
            $validEmail = $this->validator->isValidEmailString($params['email']);
            
            if (!$validEmail) {
                $this->errors['email'] = 'Email must be between ' . Validator::MIN_EMAIL_LENGTH .
                        ' and ' . Validator::MAX_EMAIL_LENGTH . ' characters in' .
                        ' with an @ symbol.';
            }
            
            $validPassword = $this->validator->isValidPasswordString($params['password']);
            
            if (!$validPassword) {
                $this->errors['password'] = 'Password must be between ' . Validator::MIN_PASSWORD_LENGTH .
                        ' and ' . Validator::MAX_PASSWORD_LENGTH . ' characters.';
            }
            
            $emailExists = $this->users->emailExists($params['email']);
            
            if ($emailExists) {
                $this->errors['email'] = 'An account already exists with that email address.';
            }
            
            $this->hasErrors = $emailExists || !$validEmail || !$validPassword;
            
            if (!$this->hasErrors) {
                $this->values['email'] = $params['email'];
                $this->values['password'] = $params['password'];
            }
        } else {
            throw new \Exception('Missing form parameters.');
        }
    }
}
