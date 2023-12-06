<?php

namespace Rumi\Session;


class PHPNativeSession implements SessionStorage {
  public function start() {
    session_start();
  }
  public function id(): string {
    return session_id();
  }
  public function has(string $key): bool {
    return isset($_SESSION[$key]);
  }
  public function get(string $key, mixed $default = null): mixed {
    return $_SESSION[$key] ?? $default;
  }

  public function set(string $key, $value) {
    $_SESSION[$key] = $value;
  }

  public function remove(string $key) {
    unset($_SESSION[$key]);
  }

  public function destroy() {
    session_destroy();
  }

}