<?php

namespace App\Models;

use Rumi\Auth\Authenticatable;

class User extends Authenticatable{

  protected array $hidden = [
    'password'
  ];

  protected array $fillable = [
    'name',
    'email',
    'password',
  ];
}