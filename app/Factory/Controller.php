<?php
namespace App\Factory;

use App\Util\Di;
use App\Controller\IndexController;
use App\Controller\UserController;
use App\Controller\ApiController;
use App\Controller\HelpController;
use App\Controller\AccountController;
use App\Controller\BizController;
use App\Util\Config;
use App\Util\View;
use App\Util\Session;
use App\Util\HeaderParams;

class Controller
{
    public function register()
    {
        $di = Di::getInstance();
        
        $config = $di->get(Config::class);
        $view = $di->get(View::class);
        $headers = $di->get(HeaderParams::class);
        
        $di->register(IndexController::class, function ($di) use ($config, $view) {
            return new IndexController($config, $view);
        });
        
        $di->register(UserController::class, function ($di) use ($config, $view, $headers) {
            $session = $di->get(Session::class);
            return new UserController($config, $view, $session, $headers);
        });
        
        $di->register(HelpController::class, function ($di) use ($config, $view) {
            return new HelpController($config, $view);
        });
        
        $di->register(AccountController::class, function ($di) use ($config, $view) {
            return new AccountController($config, $view);
        });
        
        $di->register(BizController::class, function ($di) use ($config, $view) {
            return new BizController($config, $view);
        });
        
        $di->register(ApiController::class, function ($di) use ($config, $view) {
            return new ApiController($config, $view);
        });
    }
}