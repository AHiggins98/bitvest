<?php
namespace App\Factory;

use App\Util\Di;
use App\Controller\IndexController;
use App\Controller\UserController;
use App\Controller\ApiController;
use App\Util\Config;
use App\Util\View;

class Controller
{
    public function register()
    {
        $di = Di::getInstance();
        
        $config = $di->get(Config::class);
        $view = $di->get(View::class);
        
        $di->register(IndexController::class, function ($di) use ($config, $view) {
            return new IndexController($config, $view);
        });
        
        $di->register(UserController::class, function ($di) use ($config, $view) {
            return new UserController($config, $view);
        });
        
        $di->register(ApiController::class, function ($di) use ($config, $view) {
            return new ApiController($config, $view);
        });
    }
}