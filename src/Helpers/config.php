<?php

use Rumi\Config\Config;

function config(string $key, mixed $default = null): mixed{
  return Config::get($key, $default);
}