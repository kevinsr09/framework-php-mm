<?php

namespace Rumi\Database\Drivers;

interface DatabaseDriver{

  public function connect(
    string $protocol,
    string $host,
    int $port,
    string $database,
    string $user,
    string $password,
  );


  public function statement(string $query, array $bind = []):mixed;
  public function close();

}