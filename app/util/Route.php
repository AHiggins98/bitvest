<?php
namespace App\Util;

require_once '../app/util/HeaderParams.php';

use App\Util\HeaderParams;

class Route
{
  private $resources;
  private $headers;

  public function __construct()
  {
    $this->resources = [];
    $this->headers = new HeaderParams();
  }

  public function addResources(array $resources)
  {
    $this->resources = $resources;
  }

  public function dispatch($unfilteredRequestParams)
  {
    if (!isset($unfilteredRequestParams['q'])) {
      $route = 'index/index';
    } else {
      $route = $unfilteredRequestParams['q'];
    }

    if (in_array($route, $this->resources)) {
      $routeParts = explode('/', $route);
      $controllerName = Route::toControllerName($routeParts[0]);

      if (isset($routeParts[2])) {
        $unfilteredRequestParams['version'] = $routeParts[1];
        $actionName = Route::toControllerActionName($routeParts[2]);
      } else {
        $actionName = Route::toControllerActionName($routeParts[1]);
      }

      require_once '../app/controller/' . $controllerName . '.php';
      $fullyQualifiedControllerName = "App\Controller\\$controllerName";
      $controller = new $fullyQualifiedControllerName;
      call_user_func([$controller, $actionName], $unfilteredRequestParams);
    } else {
      $this->headers->setResponseCode(404);
      echo '404 Not Found';
    }
  }

  public function toControllerName($str)
  {
    $routeWords = explode('-', $str);
    foreach ($routeWords as $routeWord) {
      $controllerWords[] = ucfirst($routeWord);
    }
    return implode('', $controllerWords) . 'Controller';
  }

  public function toControllerActionName($str)
  {
    $routeWords = explode('-', $str);
    foreach ($routeWords as $i => $routeWord) {
      if ($i == 0) {
        $actionWords[] = $routeWord;
      } else {
        $actionWords[] = ucfirst($routeWord);
      }
    }
    return implode('', $actionWords) . 'Action';
  }
}


