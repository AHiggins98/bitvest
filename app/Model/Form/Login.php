<?php

namespace App\Model\Form;

use App\Util\Validator;
use App\Model\Auth;
use App\Util\Session;

class Login
{
    private $validator;
    private $auth;
    private $session;
    
    public function __construct(Validator $validator, Session $session, Auth $auth)
    {
        $this->validator = $validator;
        $this->session = $session;
        $this->auth = $auth;
    }
    
    public function getVars()
    {
        $hasErrors = $this->session->get('hasErrors');
        $formErrors = $this->session->get('formErrors');
        $email = $this->session->get('formValues')['email'];
        
        $this->session->set('hasErrors', false);
        $this->session->set('formErrors', ['email' => '']);
        $this->session->set('formValues', ['email' => '']);
        
        return [
            'hasErrors' => $hasErrors,
            'formErrors' => $formErrors,
            'formValues' => [
                'email' => $email,
            ],
        ];
    }
    
    public function validate(array $params)
    {
        if (isset($params['email']) && isset($params['password'])) {
         
            $hasErrors = false;
            $errors = [
                'email' => '',
            ];
            
            $validEmailChars = $this->validator->isEmailChars($params['email']);
            $validPasswordChars = $this->validator->isPasswordChars($params['password']);
            $correctPassword = $this->auth->checkPassword($params['email'], $params['password']);
        
            if (!$validEmailChars || !$validPasswordChars || !$correctPassword) {
                $errors['email'] = 'Incorrect email/password.';
                $hasErrors = true;
            } 
            
            return [
                'hasErrors' => $hasErrors,
                'formErrors' => $errors,
                'formValues' => [
                    'email' => $params['email'],
                ],
            ];
            
        } else {
            throw new \Exception('Missing form parameters.');
        }
    }
}