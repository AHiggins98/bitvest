<?php

namespace App\Model\Form;

use App\Util\Validator;
use App\Model\Users;
use App\Util\Session;

class Signup
{
    private $validator;
    private $users;
    private $session;
    
    public function __construct(Validator $validator, Users $users, Session $session)
    {
        $this->validator = $validator;
        $this->users = $users;
        $this->session = $session;
    }
    
    public function getVars()
    {
        $hasErrors = $this->session->get('hasErrors');
        $formErrors = $this->session->get('formErrors');
        $email = $this->session->get('formValues')['email'];
        
        $this->session->set('hasErrors', false);
        $this->session->set('formErrors', ['email' => '', 'password' => '']);
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
         
            $validEmail = $this->validator->isValidEmailString($params['email']);
            
            $errors = [
                'email' => '',
                'password' => '',
            ];
            
            if (!$validEmail) {
                $errors['email'] = 'Email must be between ' . Validator::MIN_EMAIL_LENGTH . 
                        ' and ' . Validator::MAX_EMAIL_LENGTH . ' characters in' . 
                        ' with an @ symbol.';
            }
            
            $validPassword = $this->validator->isValidPasswordString($params['password']);
            
            if (!$validPassword) {
                $errors['password'] = 'Password must be between ' . Validator::MIN_PASSWORD_LENGTH . 
                        ' and ' . Validator::MAX_PASSWORD_LENGTH . ' characters.';
            }
            
            $emailExists = $this->users->emailExists($params['email']);
            
            if ($emailExists) {
                $errors['email'] = 'An account already exists with that email address.';
            }
            
            return [
                'hasErrors' => $emailExists || !$validEmail || !$validPassword,
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