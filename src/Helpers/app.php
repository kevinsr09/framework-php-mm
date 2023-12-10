<?php

use Rumi\App;
use Rumi\Container\Container;
use Rumi\Http\Response;

function app(string $class = App::class): object{
  return Container::resolve($class);
}

function singleton(string $class, string|callable|null $build = null){
  return Container::singleton($class, $build);
}
function resolve(string $class){
  return Container::resolve($class);
}

function abort(Response $response, int $code = 400){
  app()->abort($response, $code);
}

function env(string $key, mixed $default = null): mixed{
  return $_ENV[$key] ?? $default;
}

function resoursesDirectory(): string{
  return App::$rootDirectory . '/resources';
}

