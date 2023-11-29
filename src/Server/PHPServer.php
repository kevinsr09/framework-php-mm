<?php

namespace Rumi\Server;

use Rumi\Http\HttpMethod;
use Rumi\Http\Request;
use Rumi\Http\Response;

class PHPServer implements Server{


  public function getRequest():Request{
    
    return (new Request())
      ->setUri(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH))
      ->setMethod(HttpMethod::from($_SERVER['REQUEST_METHOD']))
      ->setData($_POST)
      ->setQuery($_GET)
      ->setHeaders(getallheaders());
  }


  public function send_response(Response $response){
    header('Content-type: None');
    header_remove('X-Powered-By');
    header_remove('Content-type');
    http_response_code($response->status());
    $response->prepare();
    
    foreach($response->headers() as $key => $value){
      //header(sprintf('%s: %s', $key, $value));
      header("$key: $value");
    }
    
    if(!is_null($response->content())){
      print($response->content());
    }
    
  }

}