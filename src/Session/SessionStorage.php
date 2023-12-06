<?php

namespace Rumi\Session;

interface SessionStorage {

  public function start();
  public function id(): string;
  public function has(string $key): bool;
  public function get(string $key, mixed $default = null): mixed;
  public function set(string $key, $value);
  public function remove(string $key);
  public function destroy();

}