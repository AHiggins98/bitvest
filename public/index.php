<?php

error_reporting(E_ALL | E_STRICT);
ini_set('display_errors', true);

require_once '../app/Util/Autoloader.php';
require_once '../app/Util/Di.php';

use App\Util\Autoloader;
use App\Util\Di;

Di::getInstance()->get(Autoloader::class)->register();

use App\Util\Route;
use App\Factory\Util;
use App\Factory\Controller;
use App\Factory\Model;

Di::getInstance()->get(Util::class)->register();
Di::getInstance()->get(Model::class)->register();
Di::getInstance()->get(Controller::class)->register();

/** @var Route $route */
$route = Di::getInstance()->get(Route::class);

$route->addResources([
    'index/index',
    'user/login',
    'user/login-submit',
    'user/signup',
    'user/signup-submit',
    'help/faq',
    'user/account',
    'biz/start',
    'api/v1/users',
    'api/v1/jobs',
    'api/v1/job_ratings',
    'api/v1/job_history',
    'api/v1/btc_transaction_history',
]);

$route->dispatch($_REQUEST);


