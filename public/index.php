<?php

require_once '../vendor/autoload.php';

use Rumi\App;
use Rumi\Database\Model;
use Rumi\Http\Middleware;
use Rumi\Http\Request;
use Rumi\Http\Response;
use Rumi\Routing\Route;
use Rumi\Validation\Rule;

$app = App::bootstrap();


$app->router->get('/', function(Request $request){
  $response = json(['message' => 'Hello get']);
  return $response;
});

$app->router->post('/hello', function(Request $request){
  $response =  (json($request->data()))->setStatus(200);
  $response->status(200);
  return $response;
});

$app->router->get('/hello/{name}', function(Request $request){
  $response = redirect('/hello');
  return $response;
});

$app->router->post('/', function(Request $request){
  $response = (json(['message' => 'Hello post']))->setStatus(200);
  return $response;
});
$app->router->post('/data', function(Request $request){
  $response = (json($request->data()))->setStatus(200);
  return $response;
});
$app->router->post('/data/query', function(Request $request){
  $response = (json($request->query()))->setStatus(200);
  return $response;
});
$app->router->post('/data/query/{user}', function(Request $request){
  $response = (json($request->query()))->setStatus(200);
  return $response;
});

class AuthMiddleware implements Middleware{
  public function handle(Request $request, Closure $next): Response{
    if($request->headers('Authorization') !== '1234'){
      $response = (json(['message' => 'Not authorized']))->setStatus(401);
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

Route::get('/middlewares', fn ()=>json(['message' => 'Hello middlewares']))
  ->setMiddleware([ AuthMiddleware::class,HeaderMiddleware::class ]);


Route::get('/test/view', fn() => view('home', ['user'=>['name'=> 'kevin']]));


Route::get(
    '/test/view/tailwind', 
    fn() => view('about', ['image'=> 'https://cdn-icons-png.flaticon.com/512/2175/2175188.png'], 'tailwind')
  );



Route::post('/validate/messages', fn(Request $req)=>json($req->validate([
  'id' => [Rule::required(), Rule::number()],
], [
  'id' => [Rule::required()::class => 'id requerido', Rule::number()::class => 'id debe ser un numero'],

])));

Route::post('/validate', fn(Request $req)=>json($req->validate([
  'id' => ["number:10","required", "number" ],
  'email' => ["required", "email"],
])));

Route::get('/session', function(Request $request){
  return json([
      'name' => session()->get('name', "Rumi"),
      'session' => $_SESSION, 
    ])->setStatus(200);
});


Route::get('/form', fn(Request $request) => view('form'));
Route::post('/form', fn(Request $request) => json($request->validate([
  'name' => ['number'],
  'email' => ['required', 'email'],
])));
  

Route::get('/user', function(Request $request){

  return json(DB()->statement('SELECT * FROM users'));
});

class User extends Model{
  protected array $fillable = ['name', 'email'];
}

Route::post('/user', function(Request $request){

  $data = $request->validate([
    'name' => ['required'],
    'email' => ['required', 'email'],
  ]);

  return json(User::create($data)->toArray());
});


Route::get('/user/{id}', function(Request $request){
  $params = $request->validateParams($request->params(), ['id'=>['required', 'number']]);
  return json(User::find($params['id']));
});

Route::get('/firts', function(){
  return json(User::first());
});
Route::post('/where', function(Request $request){

  $key = array_keys($request->data())[0];


  return json(User::where($key, $request->data()[$key]));
});

$app->run();
