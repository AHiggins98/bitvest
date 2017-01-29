<?php
namespace App\Factory;

use App\Util\Di;
use App\Model\Auth;
use App\Util\Mysql;
use App\Model\Form\Login;
use App\Util\Validator;
use App\Model\Menu;
use App\Util\Session;

class Model
{
    public function register()
    {
        $di = Di::getInstance();
        
        $di->register(Auth::class, function ($di) {
            $mysql = $di->get(Mysql::class);
            return new Auth($mysql);
        });

        $di->register(Login::class, function ($di) {
            $validator = $di->get(Validator::class);
            return new Login($validator);
        });
        
        $di->register(Menu::class, function ($di) {
            $session = $di->get(Session::class);
            return new Menu($session);
        });        
    }
}