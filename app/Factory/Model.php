<?php
namespace App\Factory;

use App\Util\Di;

class Model
{
    public function register()
    {
        $di = Di::getInstance();
        
        $di->register(User::class, function ($di) {
            return new User();
        });        
    }
}