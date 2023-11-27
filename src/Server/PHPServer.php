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
      ->setQuery($_GET);
  }


  public function send_response(Response $response){

    header_remove('X-Powered-By');
    header_remove('Content-type');

    $response->prepare();
    http_response_code($response->status());
    foreach($response->headers() as $key => $value){
      header(sprintf('%s: %s', $key, $value));
    }

    if($response->content()){
      print($response->content()) ;
    }
  }

}