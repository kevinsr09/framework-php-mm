<?php

namespace Rumi;

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

    $app = new App();
    $app->router = new Router();
    $app->server = new PHPServer();
    $app->request = $app->server->getRequest();
    return $app;
  }

  public function run(){

    try{
      $route = $this->router->resolve($this->request);
      $this->request->setParams($route->parseParameters($this->request->uri()));
      $this->request->setRoute($route);
      $handler = $route->handler();
      $response = $handler($this->request);  
      $this->server->send_response($response);
    } catch(HttpNotFoundException $e){
      $response = Response::text('Not found')->setStatus(404);
      $this->server->send_response($response);
    }
  }
  
}