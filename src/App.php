<?php

namespace Rumi;

use Rumi\Container\Container;
use Rumi\Http\HttpNotFoundException;
use Rumi\Http\Request;
use Rumi\Http\Response;
use Rumi\Routing\Router;
use Rumi\Server\PHPServer;

class App{

  public Router $router;
  public PHPServer $server;
  public Request $request;


  public static function bootstrap(){

    $app = Container::singleton(App::class);
    $app->router = new Router();
    $app->server = new PHPServer();
    $app->request = $app->server->getRequest();
    return $app;
  }

  public function run(){

    try{
      $response = $this->router->resolve($this->request);  
      $this->server->send_response($response);
    } catch(HttpNotFoundException $e){
      $response = Response::text('Not found')->setStatus(404);
      $this->server->send_response($response);
    }
  }
  
}