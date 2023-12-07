<?php

use Rumi\Session\Session;

function session(): Session{
  return app()->session;
}


function errors(){
  return session()->get('_errors', []);
}


function error(string $key) : string{
  $erros = errors()[$key] ?? [];
  return $erros[0] ?? '';
}


function old(string $key): mixed {
  return session()->get('_old', [])[$key] ?? null;
}

