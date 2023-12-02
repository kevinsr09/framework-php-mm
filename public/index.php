<?php

require_once '../vendor/autoload.php';

use Rumi\App;
use Rumi\Http\Middleware;
use Rumi\Http\Request;
use Rumi\Http\Response;
use Rumi\Routing\Route;
use Rumi\Server\PHPServer;

$app = App::bootstrap();


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

class AuthMiddleware implements Middleware{
  public function handle(Request $request, Closure $next): Response{
    if($request->headers('Authorization') !== '1234'){
      $response = (Response::json(['message' => 'Not authorized']))->setStatus(401);
      return $response;

    }
    return $next($request);
  }
}

class HeaderMiddleware implements Middleware{
  public function handle(Request $request, Closure $next): Response{
      $response = $next($request);
      $response->setHeader('Test-middleware', '2');
      return $response;
  }
}

Route::get('/middlewares', fn ()=>Response::json(['message' => 'Hello middlewares']))
  ->setMiddleware([ AuthMiddleware::class,HeaderMiddleware::class ]);


Route::get('/test/view', fn() => Response::view('home', ['user'=>['name'=> 'kevin']]));


Route::get(
    '/test/view/tailwind', 
    fn() => Response::view('about', ['image'=> 'https://cdn-icons-png.flaticon.com/512/2175/2175188.png'], 'tailwind')
  );

$app->run();
