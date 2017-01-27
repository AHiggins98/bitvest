<?php
error_reporting(E_ALL | E_STRICT);
ini_set('display_errors', true);

require_once '../app/util/Route.php';

use App\Util\Route;

$route = new Route();

$route->addResources([
  'api/v1/users',
  'api/v1/jobs',
  'api/v1/job_ratings',
  'api/v1/job_history',
  'api/v1/btc_transaction_history',
]);

$route->dispatch($_REQUEST);


