<?php

namespace Rumi;

use Closure;

class Route{

  protected string $path;
  protected string $regex;
  protected Closure $handler;
  protected array $parameters = [];
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
    return preg_match("#^$this->regex$#", $uri);
  }

  public function hasParameters():bool{
    return count($this->parameters) > 0;
  }

  public function parseParameters(string $uri):array{
    preg_match("#^$this->regex$#", $uri, $arguments);
    $arguments = array_slice($arguments, 1);
    return array_combine($this->parameters, $arguments);  
  }


  
}