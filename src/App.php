<?php

namespace Rumi;

use Rumi\Database\Drivers\DatabaseDriver;
use Rumi\Database\Drivers\PdoDriver;
use Rumi\Database\Model;
use Rumi\Http\Exceptions\HTTPNotFoundException;
use Rumi\Http\HttpMethod;
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
  public DatabaseDriver $database; 


  public static function bootstrap(){

    $app = singleton(App::class);
    $app->router = new Router();
    $app->server = new PHPServer();
    $app->request = $app->server->getRequest();
    $app->view = new RumiEngine(__DIR__ . "/../view");
    $app->session = new Session(new PHPNativeSession());
    $app->database = new PdoDriver();
    $app ->database->connect('mysql', '127.0.0.1', 3306, 'mastermind', 'root', 'root');
    Rule::loadDeafultRules();
    Model::setDriver($app->database);    
    return $app;
  }
  
  public function run(){
    

    try{

      $this->terminate($this->router->resolve($this->request));
      
    } catch(HTTPNotFoundException $e){
      $this->terminate(Response::text('Not found')->setStatus(404));
      
    }catch(ValidationException $e){
      abort($this->back()->withErrors($e->errors()), 422);
      
    }catch(RuleNotFountException $e){
      $this->terminate(Response::text($e->getMessage())->setStatus(400));
      
    }catch(RuleParseException $e){
      $this->terminate(Response::text($e->getMessage())->setStatus(400));
    
    }catch(Throwable $e){
      abort(json([ $e::class, $e->getMessage(), $e->getTrace()]), 500);
    }
  }


  public function abort(Response $response, int $code = 400){
    $this->terminate($response->setStatus($code));
  }

  public function prepareNextRequest(){
    if($this->request->method() == HttpMethod::GET){
      session()->set('_previous', $this->request->uri());
    }
  }

  public function terminate(Response $response){
    $this->prepareNextRequest();
    $this->server->send_response($response);
    $this->database->close();
    exit();
  }

  public function back(): Response{
    return redirect(session()->get('_previous', '/'));
  }
}

  
