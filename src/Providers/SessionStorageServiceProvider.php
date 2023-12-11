<?php

namespace Rumi\Providers;

use Rumi\Session\PHPNativeSession;
use Rumi\Session\SessionStorage;

class SessionStorageServiceProvider implements ServiceProvider{

  public function registerServices(){
    match(config("session.storage", "native")){
      "native" => singleton(SessionStorage::class, fn() => new PHPNativeSession()),
    };

  }
}