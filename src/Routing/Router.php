<?php

namespace Rumi\Routing;

use Closure;
use Rumi\Http\HttpMethod;
use Rumi\Http\HttpNotFoundException;
use Rumi\Http\Request;
use Rumi\Http\Response;

class Router{
  protected array $routes = [];


  public function __construct(){

    foreach(HttpMethod::cases() as $method){
      $this->routes[$method->value] = [];
    }
  }

  public function register(HttpMethod $method, string $path, Closure $handler):Route{
    return $this->routes[$method->value][] = new Route($path, $handler);
  }
  public function resolveRoute(Request $request): Route{
    foreach($this->routes[strtoupper($request->method()->value)] as $route){
      if($route->matches($request->uri())){
        return $route;
      }
    }
    throw new HttpNotFoundException('Path not found');
  }

  public function resolve(Request $request): Response{
    $route = $this->resolveRoute($request);
    $request->setParams($route->parseParameters($request->uri()));
    $request->setRoute($route);
    $handler = $route->handler();
    $response = $handler($request);

    if($route->hasMiddlewares()){
      foreach($route->middlewares() as $middleware){
        $response = $middleware->handle($request, fn()=>Response::text('Middleware not implemented'));
      }
    }
    return $response;
  }

  public function get(string $path, Closure $handler): Route{
    return $this->register(HttpMethod::GET, $path, $handler);
  }
  public function post(string $path, Closure $handler): Route{
    return $this->register(HttpMethod::POST, $path, $handler);
  }
  public function put(string $path, Closure $handler): Route{
    return $this->register(HttpMethod::PUT, $path, $handler);
  }
  public function patch(string $path, Closure $handler): Route{
    return $this->register(HttpMethod::PATCH, $path, $handler);
  }
  public function delete(string $path, Closure $handler): Route{
    return $this->register(HttpMethod::DELETE, $path, $handler);
  }


}