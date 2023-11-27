<?php

require_once '../vendor/autoload.php';

use Rumi\Routing\Router;
use Rumi\Http\HttpNotFoundException;
use Rumi\Http\Request;
use Rumi\Http\Response;
use Rumi\Server\PHPServer;

$router = new Router();


$router->get('/', function(Request $request){
  $response = Response::json(['message' => 'Hello get']);
  return $response;
});

$router->get('/hello', function(Request $request){
  $response =  (Response::json(['message' => 'Hello']))->setStatus(200);
  $response->status(200);
  return $response;
});

$router->get('/hello/{name}', function(Request $request){
  $response = Response::redirect('/hello');
  return $response;
});

$router->post('/', function(Request $request){
  $response = (Response::json(['message' => 'Hello post']))->setStatus(200);
  return $response;
});
$router->post('/data', function(Request $request){
  $response = (Response::json($request->data()))->setStatus(200);
  return $response;
});
$router->post('/data/query', function(Request $request){
  $response = (Response::json($request->queryUrl()))->setStatus(200);
  return $response;
});

$server = new PHPServer();
try{
  $request = $server->getRequest();
  var_dump($request->uri());
  $route = $router->resolve($request);
  $request->setRoute($route);
  $handler = $route->handler();
  $response = $handler($request);

  $server->send_response($response);
} catch(HttpNotFoundException $e){

  $response = Response::text('Not found')->setStatus(404);
  $server->send_response($response);
}