<?php

namespace Rumi;

use PhpParser\ErrorHandler\Throwing;
use Rumi\Http\Exceptions\HTTPNotFoundException;
use Rumi\Http\Request;
use Rumi\Http\Response;
use Rumi\Routing\Router;
use Rumi\Server\PHPServer;
use Rumi\Session\PHPNativeSession;
use Rumi\Session\Session;
use Rumi\Validation\Exceptions\RuleNotFountException;
use Rumi\Validation\Exceptions\RuleParseException;
use Rumi\Validation\Exceptions\ValidationException;
use Rumi\Validation\Rule;
use Rumi\View\RumiEngine;
use Rumi\View\View;
use Throwable;

class App{

  public Router $router;
  public PHPServer $server;
  public Request $request;
  public View $view;
  public Session $session;


  public static function bootstrap(){

    $app = singleton(App::class);
    $app->router = new Router();
    $app->server = new PHPServer();
    $app->request = $app->server->getRequest();
    $app->view = new RumiEngine(__DIR__ . "/../view");
    $app->session = new Session(new PHPNativeSession());
    Rule::loadDeafultRules();
    
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
      $response = json($e->errors())->setStatus(400);
      $this->server->send_response($response);
    
    }catch(RuleNotFountException $e){
      $response = Response::text($e->getMessage())->setStatus(400);
      $this->server->send_response($response);

    }catch(RuleParseException $e){
      $response = Response::text($e->getMessage())->setStatus(400);
      $this->server->send_response($response);
    
    }catch(Throwable $e){
      $response = json([ $e::class, $e->getMessage(), $e->getTrace()])->setStatus(500);
      $this->server->send_response($response);
    }

  }
}
  
