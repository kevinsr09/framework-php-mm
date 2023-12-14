<?php

namespace Rumi\Providers;

use Rumi\Crypto\BCrypt;
use Rumi\Crypto\Hasher;

class CryptoServiceProvider implements ServiceProvider
{

  public function registerServices()
  {
    match(config('crypto.hasher', 'bcrypt')){
      'bcrypt' => singleton(Hasher::class, BCrypt::class),
    };
  }

}