<?php

namespace Rumi\Routing;

use Closure;
use Rumi\Container\DependencyInjection;
use Rumi\Http\Exceptions\HTTPNotFoundException;
use Rumi\Http\HttpMethod;
use Rumi\Http\Request;
use Rumi\Http\Response;

class Router{
  protected array $routes = [];


  public function __construct(){

    foreach(HttpMethod::cases() as $method){
      $this->routes[$method->value] = [];
    }
  }

  public function register(HttpMethod $method, string $path, Closure|array $handler):Route{
    return $this->routes[$method->value][] = new Route($path, $handler);
  }
  public function resolveRoute(Request $request): Route{

    foreach($this->routes[strtoupper($request->method()->value)] as $route){

      if($route->matches($request->uri())){

        return $route;
      }
    }
    throw new HTTPNotFoundException('Path not found');
  }

  public function resolve(Request $request): Response{

    $route = $this->resolveRoute($request);

    $request->setRoute($route);
    $request->setParams($route->parseParameters($request->uri()));
    $handler = $route->handler();


    if(is_array($handler)){
      $controller = new $handler[0];
      $handler[0] = $controller;
    }

    try{

      $params = DependencyInjection::resolve($handler, $request->params());
    }catch(HTTPNotFoundException $e){
      throw $e;
    }


    return $this->runMiddleware($request, $route->middlewares(), fn() => call_user_func($handler, ...$params));

  }

  private function runMiddleware(Request $request, array $middlewares, Closure $target):Response{
    if (count($middlewares) == 0) {
      return $target();
    }

    return $middlewares[0]->handle($request, fn($request)=> $this->runMiddleware($request, array_slice($middlewares, 1), $target));
  }

  public function get(string $path, Closure|array $handler): Route{
    return $this->register(HttpMethod::GET, $path, $handler);
  }
  public function post(string $path, Closure|array $handler): Route{
    return $this->register(HttpMethod::POST, $path, $handler);
  }
  public function put(string $path, Closure|array $handler): Route{
    return $this->register(HttpMethod::PUT, $path, $handler);
  }
  public function patch(string $path, Closure|array $handler): Route{
    return $this->register(HttpMethod::PATCH, $path, $handler);
  }
  public function delete(string $path, Closure|array $handler): Route{
    return $this->register(HttpMethod::DELETE, $path, $handler);
  }


}