<?php

namespace App\Model\Form;

use App\Util\Validator;
use App\Model\Auth;
use App\Util\Session;

class Login extends AbstractForm
{
    private $validator;
    private $auth;
    private $session;
    
    public function __construct(Validator $validator, Session $session, Auth $auth)
    {
        parent::__construct(['email', 'password']);
        $this->validator = $validator;
        $this->session = $session;
        $this->auth = $auth;
    }
    
    /**
     * Validates the parameters. If the validation passes, the class values
     * will be updated to what was submitted.
     *
     * @param array $params
     * @throws \Exception
     */
    public function validate(array $params)
    {
        if (isset($params['email']) && isset($params['password'])) {
            $this->hasErrors = false;
            
            $validEmailChars = $this->validator->isEmailChars($params['email']);
            $validPasswordChars = $this->validator->isPasswordChars($params['password']);
            $correctPassword = $this->auth->checkPassword($params['email'], $params['password']);
        
            if (!$validEmailChars || !$validPasswordChars || !$correctPassword) {
                $this->errors['email'] = 'Incorrect email/password.';
                $this->hasErrors = true;
            }
            
            $this->values['email'] = $params['email'];
        } else {
            throw new \Exception('Missing form parameters.');
        }
    }
}
