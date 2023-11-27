<?php

require_once '../vendor/autoload.php';

use Rumi\Routing\Router;
use Rumi\Http\HttpNotFoundException;
use Rumi\Routing\Request;
use Rumi\Server\PHPServer;

$router = new Router();


$router->get('/', function(){
  return 'Hello get';
});

$router->get('/hello', function(){
  return 'Hello World';
});

$router->get('/hello/{name}', function(){
  return 'Hello name';      
});

$router->post('/', function(){
  return 'Hello post';
});

try{
  
  $route = $router->resolve(new Request(new PHPServer()));

  $handler = $route->handler();

  print($handler());
  
} catch(HttpNotFoundException $e){
  echo $e->getMessage();
  http_response_code(404);
}