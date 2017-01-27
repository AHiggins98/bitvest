<?php
namespace App\Util;

class Route
{
  public function __construct()
  {
    $this->resources = [];
  }

  public function addResources(array $resources)
  {
    $this->resources = $resources;
  }

  public function dispatch($unfilteredRequestParams)
  {
    $route = $unfilteredRequestParams['q'];
    if (in_array($route, $this->resources)) {
      $routeParts = explode('/', $route);
      $controllerName = Route::toControllerName($routeParts[0]);
      $unfilteredRequestParams['version'] = $routeParts[1];
      $actionName = Route::toControllerActionName($routeParts[2]);
      require_once '../app/controller/' . $controllerName . '.php';
      $fullyQualifiedControllerName = "App\Controller\\$controllerName";
      $controller = new $fullyQualifiedControllerName;
      call_user_func([$controller, $actionName], $unfilteredRequestParams);
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


