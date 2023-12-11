<?php

namespace Rumi;

use Dotenv\Dotenv;
use Rumi\Config\Config;
use Rumi\Database\Drivers\DatabaseDriver;
use Rumi\Database\Model;
use Rumi\Http\Exceptions\HTTPNotFoundException;
use Rumi\Http\HttpMethod;
use Rumi\Http\Request;
use Rumi\Http\Response;
use Rumi\Routing\Router;
use Rumi\Server\PHPServer;
use Rumi\Server\Server;
use Rumi\Session\Session;
use Rumi\Session\SessionStorage;
use Rumi\Validation\Exceptions\RuleNotFountException;
use Rumi\Validation\Exceptions\RuleParseException;
use Rumi\Validation\Exceptions\ValidationException;
use Throwable;


class App{

  public static string $rootDirectory;
  public Router $router;
  public PHPServer $server;
  public Request $request;
  public Session $session;
  public DatabaseDriver $database; 


  public static function bootstrap(string $rootDirectory): App{

    self::$rootDirectory = $rootDirectory;


    $app = singleton(App::class);

    
     $app
      ->loadConfig();
      $app
      ->loadServicesProviders('boot');
      
    $app
      ->loadHttpHandlers();

    $app
      ->setupDatabaseConnection();


    $app
      ->loadServicesProviders('runtime');    

      return $app;
    }
    
    
    protected function loadConfig(): self{
      Dotenv::createImmutable(self::$rootDirectory)->load();
      Config::load(self::$rootDirectory . '/config');
      return $this;
    }
    
    protected function loadServicesProviders(string $key): self{
      foreach((config("providers.$key", [])) as $providerClass){

        $provider = new $providerClass();
        $provider->registerServices();
      }
      
      return $this;
    }
    protected function loadHttpHandlers(): self{
      
      $this->router = singleton(Router::class);
      $this->server = app(Server::class);
      $this->request = $this->server->getRequest();
      $this->session = new Session(app(SessionStorage::class));

      return $this;
    }
    
    public function setupDatabaseConnection(): self{
      
      $this->database = app(DatabaseDriver::class);
      $this->database->connect(
        config('database.connection', 'mysql'), 
        config('database.host', '127.0.0.1'), 
        config('database.port', 3306), 
        config('database.database', 'mastermind'), 
        config('database.username', 'root'), 
        config('database.password', '')
      );
      
      Model::setDriver($this->database);  

      return $this;
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

  
