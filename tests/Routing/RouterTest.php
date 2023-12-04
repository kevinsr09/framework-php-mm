<?php

namespace Rumi\Tests\Routing;

use Closure;
use PHPUnit\Framework\TestCase;
use Rumi\Http\HttpMethod;
use Rumi\Http\Request;
use Rumi\Http\Response;
use Rumi\Routing\Router;

class RouterTest extends TestCase {

  private function requestMock(string $uri, HttpMethod $method){
    $requestMock = (new Request())
      ->setUri($uri)
      ->setMethod($method);

    return $requestMock;
  }

  public function test_resolve_baseic_route(){
    $router = new Router();
    $path = '/test';
    $handler = fn()=> 'test';
    $router->get($path, $handler);
    $responseRouter = $router->resolveRoute($this->requestMock($path, HttpMethod::GET));    
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
      $responseRouter = $router->resolveRoute($this->requestMock($path, $method));
      $this->assertEquals($handler, $responseRouter->handler());
    }
  }

  public function test_resolve_baseic_route_with_middlewares_setData(){
    
    $uri = '/test/middlewares';
    $method = HttpMethod::GET;
    $data = ['user' => 'kevin'];
    $router = new Router();
    $handler = fn($request)=> Response::json($request->data());
    $router->get($uri, $handler);

    $request = ((new Request()))
      ->setData($data)
      ->setUri($uri)
      ->setMethod($method);

    $route = $router->resolveRoute($request);
    
    $middleware = new class {
      public function handle(Request $request, Closure $next): Response{
        $request->setData(['user'=> 'kevin']);
        return $next($request);
      }
    };

    $route->setMiddleware([$middleware::class]);

    $response = $router->resolve($request);


    $this->assertEquals('{"user":"kevin"}', $response->content());

    
  }

  
}