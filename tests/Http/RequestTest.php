<?php

namespace Rumi\Tests\Http;

use PHPUnit\Framework\TestCase;
use Rumi\Http\HttpMethod;
use Rumi\Http\Request;
use Rumi\Routing\Route;

class RequestTest extends TestCase{

  public function test_request_simple(){

    $path = '/hello';
    $method = HttpMethod::GET;
    $data = ['name' => 'Rumi', 'age' => 22];
    $query = ['filter' => 'yes', 'login' => 'yes'];

    $request = (new Request())
      ->setUri($path)
      ->setMethod($method)
      ->setData($data)
      ->setQuery($query);
      
    $this->assertEquals('/hello', $request->uri());
    $this->assertEquals(HttpMethod::GET, $request->method());
    $this->assertEquals($data, $request->data());
    $this->assertEquals($query, $request->query());
    
  }

  public function test_request_selectecd_param_query(){

    $handler = fn()=> 'test';
    $uri = '/test/{id}';
    $testUri = '/test/1';
    $testUri2 = '/test/1/23';
    $params = ['id'=>1];
    $params2 = ['ids'=>1];

    $request = (new Request())
      ->setUri($uri)
      ->setMethod(HttpMethod::GET)
      ->setRoute(new Route($uri, $handler));
    
    $this->assertEquals($handler, $request->route()->handler());
    $this->assertTrue($request->route()->matches($testUri));
    $this->assertFalse($request->route()->matches($testUri2));
    $this->assertFalse($request->route()->matches($testUri2));
    $this->assertEquals($params, $request->route()->parseParameters($testUri));
    $this->assertNotEquals($params2, $request->route()->parseParameters($testUri));
    
  }

  public function test_request_selectecd_query(){
    $handler = fn()=> 'test';
    $uri = '/test/1?user=kevin';
    $query = ['user' => 'kevin'];
    $userTest = 'kevin';
    $request = (new Request())
      ->setUri($uri)
      ->setMethod(HttpMethod::GET)
      ->setQuery($query);

    $this->assertEquals($query, $request->query());
    $this->assertEquals($userTest, $request->query('USer'));
    
  }


}
