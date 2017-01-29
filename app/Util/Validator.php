<?php

namespace App\Util;

class Validator
{
    const AZ = 'abcdefghijklmnopqrstuvwxyz';
    const NUMS = '0123456789';
    const SPECIAL = '`-=[];\',./\\~!@#$%^&*()_+{}:"<>?';
    const MIN_PASSWORD_LENGTH = 5;
    const MAX_PASSWORD_LENGTH = 128;
    const MIN_EMAIL_LENGTH = 5;
    const MAX_EMAIL_LENGTH = 128;
    
    public function isEmailChars($email)
    {
        return $email == filter_var($email, FILTER_VALIDATE_EMAIL);
    }
    
    public function isPasswordChars($password)
    {
        return strlen($password) == strspn($password, 
            self::AZ . strtoupper(self::AZ) . self::NUMS . self::SPECIAL);
    }
   
    public function isValidEmail($email)
    {
        return strlen($email) >= self::MIN_EMAIL_LENGTH && 
               strlen($email) <= self::MAX_EMAIL_LENGTH &&
               $this->isEmailChars($email);
    }
    
    public function isValidPasswordLength($password)
    {
        return strlen($password) >= self::MIN_PASSWORD_LENGTH &&
               strlen($password) <= self::MAX_PASSWORD_LENGTH &&
               $this->isPasswordChars($password);
    }
}