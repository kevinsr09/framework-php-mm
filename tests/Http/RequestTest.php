<?php

namespace Rumi\Http;

use PHPUnit\Framework\TestCase;

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


}
