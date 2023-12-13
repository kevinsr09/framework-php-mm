<?php

use Rumi\Auth\Auth;
use Rumi\Auth\Authenticatable;

function auth(): ?Authenticatable{
  return Auth::user();
}


function isGuest(): bool{
  return Auth::isGuest();
}