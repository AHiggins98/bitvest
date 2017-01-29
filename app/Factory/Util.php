<?php
namespace App\Factory;

use App\Util\Di;
use App\Util\Route;
use App\Util\HeaderParams;

class Util
{   
    public function register()
    {
        $di = Di::getInstance();
        $di->register(Route::class, function ($di) {
            $headers = $di->get(HeaderParams::class);
            return new Route($headers);
        });
    }
}