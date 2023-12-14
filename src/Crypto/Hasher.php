<?php

namespace Rumi\Crypto;

interface Hasher
{
  public function hash(string $value): string;
  public function verify(string $value, string $hash): bool;
}