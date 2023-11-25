<?php

namespace Rumi\Tests;

use Closure;
use PHPUnit\Framework\TestCase;
use Rumi\HttpMethod;
use Rumi\Route;
use Rumi\Router;

class RouterTest extends TestCase {

  public function test_resolve_baseic_route(){
    $router = new Router();
    $path = '/test';
    $handler = fn()=> 'test';
    $router->get($path, $handler);
    $this->assertEquals($handler, 
    $router->resolve($path, HttpMethod::GET->value));
  }

  public function test_resolve_baseic_route_with_multiple_methods(){
    $router = new Router();
    $routes = [
      ['/test', fn()=> 'test get'],
      ['/test', HttpMethod::POST->value, fn()=> 'test post'],
      ['/test', HttpMethod::PUT->value, fn()=> 'test put'],
      ['/test', HttpMethod::DELETE->value, fn()=> 'test delete'],

      ['/test/id/user', fn()=> 'test get id'],
      ['/test/id/user', HttpMethod::POST->value, fn()=> 'test post id'],
      ['/test/id/user', HttpMethod::PUT->value, fn()=> 'test put id'],
      ['/test/id/user', HttpMethod::DELETE->value, fn()=> 'test delete id'],
    ];

    foreach($routes as [$path, $method, $handler]){
      $router->{strtolower($method)}($path, $handler);
    }
    foreach($routes as [$path, $method, $handler]){
      $this->assertEquals($handler, $router->resolve($path, $method));
    }
  }

  
}