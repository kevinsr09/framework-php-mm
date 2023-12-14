<?php

use Rumi\Session\Session;

function session(): Session{
  return app()->session;
}


function errors(): array{
  return session()->get('_errors', []);
}


function error(string $key) : mixed{
  return errors()[$key] ?? null;
}


function old(string $key): mixed {
  return session()->get('_old', [])[$key] ?? null;
}

