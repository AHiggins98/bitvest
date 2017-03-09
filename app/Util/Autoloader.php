<?php
namespace App\Util;

class Autoloader
{
    public function register()
    {
        spl_autoload_register(function ($className) {
            $file = dirname(__FILE__) . 
                    '/../' . 
                    str_replace(['App', '\\'], ['', '/'], $className) 
                    . '.php';
            
            if (!is_readable($file)) {
                return;
            }
            
            include $file;
        });
    }
    
    public function registerTests()
    {
        // TODO
    }
}