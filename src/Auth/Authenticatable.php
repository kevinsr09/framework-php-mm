<?php

namespace Rumi\Auth;

use Rumi\Auth\Authenticators\Authenticator;
use Rumi\Database\Model;

class Authenticatable extends Model{

  public function id(): int|string{
    return $this->{$this->primaryKey};
  }


  public function login(){
    app(Authenticator::class)->login($this);
  }
  public function logout(){
    app(Authenticator::class)->logout($this);
  }

  public function isAuthenticated(): bool{

    return app(Authenticator::class)->isAuthenticated($this);
  }

}