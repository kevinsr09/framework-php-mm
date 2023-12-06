<?php

namespace Rumi\Session;

class Session {

  public const FLASH_KEY  = '_flash';

  public function __construct(protected SessionStorage $storage) {
    $this->storage->start();

    if(!$this->storage->has(self::FLASH_KEY)){
      $this->storage->set(self::FLASH_KEY, [
        'new' => [],
        'old' => [],
      ]);
    }
  }

  public function __destruct(){
    $this->ageFlashData();
  }
  public function flash(string $key, mixed $value) {

    $this->storage->set($key, $value);
    $flash = $this->storage->get(self::FLASH_KEY);
    $flash['new'][] = $key;
    $this->storage->set(self::FLASH_KEY, $flash);
  }
  public function start() {
    $this->storage->start();
  }

  public function ageFlashData() {

    $flash = $this->storage->get(self::FLASH_KEY);
    foreach($flash['old'] as $key){
      $this->storage->remove($key);
    }

    $flash['old'] = $flash['new'];
    $flash['new'] = [];
    $this->storage->set(self::FLASH_KEY, $flash);
  }
  public function id(): string {
    return $this->storage->id();
  }

  public function has(string $key): bool {
    return $this->storage->has($key);
  }

  public function get(string $key, mixed $default = null): mixed {
    return $this->storage->get($key, $default);
  }

  public function set(string $key, $value) {
    $this->storage->set($key, $value);
  }

  public function remove(string $key) {
    $this->storage->remove($key);
  }

  public function destroy() {
    $this->storage->destroy();
  }

}
