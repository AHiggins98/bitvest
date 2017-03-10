<?php

namespace App\Model\Form;

use App\Util\Validator;
use App\Model\Users;
use App\Util\Session;

class Signup extends AbstractForm
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
            
            if (!$emailExists && $validEmail) {
                // They may have to re-type their password, but we'll redisplay the email.
                $this->values['email'] = $params['email'];
            }
            
            if (!$this->hasErrors) {
                $this->values['password'] = $params['password'];
            } else {
                $this->values['password'] = '';
            }
        } else {
            throw new \Exception('Missing form parameters.');
        }
    }
}
