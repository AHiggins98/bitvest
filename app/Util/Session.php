<?php

namespace App\Util;

/**
 * @codeCoverageIgnore
 */
class Session
{
    public function __construct()
    {
        session_start();
    }
    
    public function end()
    {
        session_destroy();  
    }
    
    public function regenerate()
    {
        session_regenerate_id(true);
    }
    
    public function set($var, $value)
    {
        $_SESSION[$var] = $value;
    }
    
    public function get($var)
    {
        if (isset($_SESSION[$var])) {
            return $_SESSION[$var];
        } 
        return null;
    }
    
    public function delete($var)
    {
        unset($_SESSION[$var]);
    }
}