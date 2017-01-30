<?php

error_reporting(E_ALL | E_STRICT);
ini_set('display_errors', true);

require_once '../app/Util/Autoloader.php';
require_once '../app/Util/Di.php';

use App\Util\Autoloader;
use App\Util\Di;
use App\Util\Route;
use App\Factory\Util;
use App\Factory\Controller;
use App\Factory\Model;
use App\Util\View;

Di::getInstance()->get(Autoloader::class)->register();
Di::getInstance()->get(Util::class)->register();
Di::getInstance()->get(Model::class)->register();
Di::getInstance()->get(Controller::class)->register();

/** @var Route $route */
$route = Di::getInstance()->get(Route::class);

$route->addResources([
    'index/index',
    'index/error',
    'user/login',
    'user/login-submit',
    'user/signup',
    'user/signup-submit',
    'user/logout',
    'user/verify',
    'help/faq',
    'user/account',
    'biz/start',
    'api/v1/users',
    'api/v1/jobs',
    'api/v1/job_ratings',
    'api/v1/job_history',
    'api/v1/btc_transaction_history',
]);

try {
    $route->dispatch($_REQUEST);
} catch (\Exception $e) {
    Di::getInstance()->get(View::class)->render('error');
    throw $e;
    
}

