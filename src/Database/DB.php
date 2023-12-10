<?php

namespace Rumi\Database;

use Rumi\Database\Drivers\DatabaseDriver;

class DB{

  public static function statement(string $sql, array $params = []){
    return app(DatabaseDriver::class)->statement($sql, $params);
  }
}