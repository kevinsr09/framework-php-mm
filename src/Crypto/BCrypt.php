<?php
namespace Rumi\Crypto;

class BCrypt implements Hasher
{
  public function hash(string $value): string
  {
    return password_hash($value, PASSWORD_BCRYPT);
  }

  public function verify(string $value, string $hash): bool
  {
    return password_verify($value, $hash);
  }

}