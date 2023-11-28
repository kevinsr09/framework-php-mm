<?php

namespace Rumi\Test\Http;

use PHPUnit\Framework\TestCase;
use Rumi\Http\Response;

class ResponseTest extends TestCase{

  public function test_response_with_set_headers(){
    $type = 'Content-Type';
    $value = 'application/json';
    $response = (new Response())
      ->setHeader($type, $value);

    $this->assertEquals(strtolower($value), $response->headers()[strtolower($type)]);
    $this->assertEquals($value, $response->headers($type));

    // no find capitalize case
    $this->assertArrayHasKey(strtolower($type), $response->headers());


  }
}