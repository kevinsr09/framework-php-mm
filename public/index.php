<?php

require_once '../vendor/autoload.php';

use Rumi\Router;
use Rumi\HttpNotFoundException;
use Rumi\Route;

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
  // $handle = $router->resolve($_SERVER['REQUEST_URI'], $_SERVER['REQUEST_METHOD']);
  // print($handle());

  $route = $router->resolve($_SERVER['REQUEST_URI'], $_SERVER['REQUEST_METHOD']);

  $handler = $route->handler();

  print($handler());
  
} catch(HttpNotFoundException $e){
  echo $e->getMessage();
  http_response_code(404);
}