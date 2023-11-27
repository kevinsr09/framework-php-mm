<?php

namespace Rumi\Tests;
use PHPUnit\Framework\TestCase;
use Rumi\Http\HttpMethod;
use Rumi\Routing\Request;
use Rumi\Routing\Router;
use Rumi\Server\Server;

class RouterTest extends TestCase {

  private function mock_server(string $uri, HttpMethod $method){
    $mock = $this->getMockBuilder(Server::class)->getMock();
    $mock->method('request_uri')->willReturn($uri);
    $mock->method('request_method')->willReturn($method);

    return new Request($mock);
  }

  public function test_resolve_baseic_route(){
    $router = new Router();
    $path = '/test';
    $handler = fn()=> 'test';
    $router->get($path, $handler);
    $responseRouter = $router->resolve($this->mock_server($path, HttpMethod::GET));    
    $this->assertEquals($handler, $responseRouter->handler());
    }

  public function test_resolve_baseic_route_with_multiple_methods(){
    $router = new Router();
    $routes = [
      ['/test', HttpMethod::GET,fn()=> 'test get'],
      ['/test', HttpMethod::POST, fn()=> 'test post'],
      ['/test', HttpMethod::PUT, fn()=> 'test put'],
      ['/test', HttpMethod::DELETE, fn()=> 'test delete'],

      ['/test/id/user', HttpMethod::GET,fn()=> 'test get id'],
      ['/test/id/user', HttpMethod::POST, fn()=> 'test post id'],
      ['/test/id/user', HttpMethod::PUT, fn()=> 'test put id'],
      ['/test/id/user', HttpMethod::DELETE, fn()=> 'test delete id'],
    ];

    foreach($routes as [$path, $method, $handler]){
      $router->{strtolower($method->value)}($path, $handler);
    }
    foreach($routes as [$path, $method, $handler]){
      $responseRouter = $router->resolve($this->mock_server($path, $method));
      $this->assertEquals($handler, $responseRouter->handler());
    }
  }

  
}