<?php

use Rumi\App;
use Rumi\Container\Container;
use Rumi\Http\Response;

function app(){
  return Container::resolve(App::class);
}

function singleton(string $class){
  return Container::singleton($class);
}
function resolve(string $class){
  return Container::resolve($class);
}

function abort(Response $response, int $code = 400){
  app()->abort($response, $code);
}