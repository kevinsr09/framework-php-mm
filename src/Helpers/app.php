<?php

use Rumi\App;
use Rumi\Container\Container;

function app(){
  return Container::resolve(App::class);
}

function singleton(string $class){
  return Container::singleton($class);
}
function resolve(string $class){
  return Container::resolve($class);
}