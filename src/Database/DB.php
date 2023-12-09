<?php

namespace Rumi\Database;

class DB{

  public static function statement(string $sql, array $params = []){
    return app()->database->statement($sql, $params);
  }
}