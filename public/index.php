<?php

require_once '../vendor/autoload.php';

use Rumi\App as RumiApp;
use Rumi\Http\Request;
use Rumi\Http\Response;
use Rumi\Server\PHPServer;

$app = RumiApp::bootstrap();


$app->router->get('/', function(Request $request){
  $response = Response::json(['message' => 'Hello get']);
  return $response;
});

$app->router->post('/hello', function(Request $request){
  $response =  (Response::json($request->data()))->setStatus(200);
  $response->status(200);
  return $response;
});

$app->router->get('/hello/{name}', function(Request $request){
  $response = Response::redirect('/hello');
  return $response;
});

$app->router->post('/', function(Request $request){
  $response = (Response::json(['message' => 'Hello post']))->setStatus(200);
  return $response;
});
$app->router->post('/data', function(Request $request){
  $response = (Response::json($request->data()))->setStatus(200);
  return $response;
});
$app->router->post('/data/query', function(Request $request){
  $response = (Response::json($request->query()))->setStatus(200);
  return $response;
});
$app->router->post('/data/query/{user}', function(Request $request){
  $response = (Response::json($request->query()))->setStatus(200);
  return $response;
});

$app->run();
