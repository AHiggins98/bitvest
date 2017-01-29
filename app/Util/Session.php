<?php

namespace App\Util;

class Session
{
    public function __construct()
    {
        session_start();
    }
    
    public function set($var, $value)
    {
        $_SESSION[$var] = $value;
    }
    
    public function get($var)
    {
        return $_SESSION[$var];
    }
}