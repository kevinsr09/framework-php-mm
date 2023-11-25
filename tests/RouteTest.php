<?php

namespace Rumi\Tests;

use Closure;
use PHPUnit\Framework\TestCase;
use Rumi\Route;

class RouteTest extends  TestCase {

  public function routesWithParams(){
    return [
      ['/test/{id}', '/test/1',  fn ()=>'test', ['id'=>1]],
      ['/test/{id}/{user}', '/test/1/1882',  fn ()=>'test', ['id'=>1, 'user'=>1882]],
      ['/test/{id}/{user}/data', '/test/12/3112/data',  fn ()=>'test', ['id'=>12, 'user'=>3112]],
    ];
  }
  /**
  * @dataProvider routesWithParams
  */
  public function test_resolve_parametric_route(string $path, string $uri, Closure $handler){

    $route = new Route($path, $handler);

    $this->assertTrue($route->matches($uri));
    $this->assertEquals($handler, $route->handler());

  }

  /**
   * @dataProvider routesWithParams
   */
  public function test_resolve_baseic_route_with_parameters(string $path, string $uri, Closure $handler, array $params){
    $route = new Route($path, $handler);
    $this->assertTrue($route->hasParameters());
    $this->assertEquals($params, $route->parseParameters($uri));
  }


  /**
   * @dataProvider routesWithParams
   */

  public function test_when_the_route_ends_in_slash(string $path, string $uri){

    $route = new Route($path, fn()=> 'test');
    $this->assertTrue($route->matches("$uri/"));
  }
}
