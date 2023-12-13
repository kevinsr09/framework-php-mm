<?php

namespace Rumi\Providers;

use Rumi\Auth\Authenticators\Authenticator;
use Rumi\Auth\Authenticators\SessionAuthenticator;

class AuthServiveProvider implements ServiceProvider
{
  public function registerServices() {

    match(config("auth.method", "session")){
      "session" => singleton(Authenticator::class, fn() => new SessionAuthenticator()),
    };

  }
}