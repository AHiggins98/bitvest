<?php
namespace App\Factory;

use App\Util\Di;
use App\Util\Route;
use App\Util\HeaderParams;
use App\Util\Mysql;
use App\Util\Config;

class Util
{   
    public function register()
    {
        $di = Di::getInstance();
        
        $di->register(Route::class, function ($di) {
            $headers = $di->get(HeaderParams::class);
            return new Route($headers);
        });
        
        $di->register(Mysql::class, function ($di) {
            $config = $di->get(Config::class);
            return new Mysql($config);
        });
    }
}