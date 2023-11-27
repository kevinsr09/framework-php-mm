<?php

require_once '../vendor/autoload.php';

use Rumi\Routing\Router;
use Rumi\Http\HttpNotFoundException;
use Rumi\Http\Request;
use Rumi\Http\Response;
use Rumi\Server\PHPServer;

$router = new Router();


$router->get('/', function(Request $request){
  $response = new Response();
  $response->setHeader('Content-Type', 'text/html');
  $response->setContent(json_encode(['message' => 'Hello get']));
  $response->status(201);
  return $response;
});

$router->get('/hello', function(Request $request){
  $response = new Response();
  $response->setHeader('Content-Type', 'text/html');
  $response->setContent(json_encode(['message' => 'Hello']));
  $response->status(200);
  return $response;
});

$router->get('/hello/{name}', function(Request $request){
  $response = new Response();
  $response->setHeader('Content-Type', 'text/html');
  $response->setContent(json_encode(['message' => 'Hello name']));
  $response->status(200);
  return $response;
});

$router->post('/', function(Request $request){
  $response = new Response();
  $response->setHeader('Content-Type', 'text/html');
  $response->setContent(json_encode(['message' => 'Hello post']));
  $response->status(200);
  return $response;
});

$server = new PHPServer();
try{
  $request = new Request($server);
  $route = $router->resolve($request);
  $handler = $route->handler();
  $response = $handler($request);

  $server->send_response($response);
} catch(HttpNotFoundException $e){

  $response = new Response();
  $response->setHeader('Content-Type', 'text/plain');
  $response->setStatus(404);
  $response->setContent('Not found');
  $server->send_response($response);
}