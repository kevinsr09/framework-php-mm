<?php

namespace Rumi;

use Rumi\Http\Exceptions\HTTPNotFoundException;
use Rumi\Http\Request;
use Rumi\Http\Response;
use Rumi\Routing\Router;
use Rumi\Server\PHPServer;
use Rumi\Validation\Exceptions\ValidationException;
use Rumi\View\RumiEngine;
use Rumi\View\View;

class App{

  public Router $router;
  public PHPServer $server;
  public Request $request;
  public View $view;


  public static function bootstrap(){

    $app = singleton(App::class);
    $app->router = new Router();
    $app->server = new PHPServer();
    $app->request = $app->server->getRequest();
    $app->view = new RumiEngine(__DIR__ . "/../view");
    
    return $app;
  }

  public function run(){

    try{
      $response = $this->router->resolve($this->request);  
      $this->server->send_response($response);
    } catch(HTTPNotFoundException $e){
      $response = Response::text('Not found')->setStatus(404);
      $this->server->send_response($response);
    }catch(ValidationException $e){

      $response = Response::json($e->errors())->setStatus(400);
      $this->server->send_response($response);
    }

  }
}
  
