<?php

namespace Rumi\Routing;

use Closure;
use Rumi\App;
use Rumi\Container\Container;
use Rumi\Http\HttpMethod;
use Rumi\Http\Middleware;

class Route{

  protected string $path;
  protected string $regex;
  protected Closure $handler;
  protected array $parameters = [];
  protected array $middlewares = [];
  public function __construct(string $path, Closure $handler){
    $this->path = $path;
    $this->handler = $handler;
    $this->regex = preg_replace('/\{([a-zA-Z]+)\}/', '([a-zA-Z0-9]+)', $path);
    preg_match_all('/\{([a-zA-Z]+)\}/', $path, $this->parameters);
    $this->parameters = $this->parameters[1];
  }

  public function path(){
    return $this->path;
  }

  public function handler(){
    return $this->handler;
  }

  public function matches(string $uri):bool{
    return preg_match("#^$this->regex/?$#", $uri);
  }

  public function hasParameters():bool{
    return count($this->parameters) > 0;
  }

  public function parseParameters(string $uri):array{
    preg_match("#^$this->regex$#", $uri, $arguments);
    $arguments = array_slice($arguments, 1);
    return array_combine($this->parameters, $arguments);  
  }

  public function middlewares():array{
    return $this->middlewares;
  }

  public function hasMiddlewares():bool{
    return count($this->middlewares) > 0;
  }

  public function setMiddleware(array $middlewares):self{
    $this->middlewares = array_map(fn($middleware) => new $middleware(), $middlewares);
    return $this;
  }


  public static function get(string $path, Closure $handler):Route{
    return app()->router->get($path, $handler);
    
  }
  public static function post(string $path, Closure $handler):Route{
    return app()->router->post($path, $handler);
    
  }

  public static function load(string $routesDirectory){

    $routes = glob($routesDirectory . "/*.php");
    foreach($routes as $route){
      require_once $route;
    }
  }

}

  
