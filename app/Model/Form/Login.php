<?php

namespace App\Model\Form;

use App\Util\Validator;

class Login
{
    private $validator;
    
    public function __construct(Validator $validator)
    {
        $this->validator = $validator;
    }
    
    public function validate(array $params)
    {
        if (isset($params['email']) && isset($params['password'])) {
         
            $validEmailChars = $this->validator->isEmailChars($params['email']);
            $validPasswordChars = $this->validator->isPasswordChars($params['password']);
            
            if ($validEmailChars && $validPasswordChars) {
                return [
                    'email' => $params['email'],
                    'password' => $params['password']
                ];
            } else {
                return false;
            }
            
        } else {
            throw new \Exception('Parameters required.');
        }
    }
}