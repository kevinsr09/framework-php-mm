<?php

namespace Rumi\Providers;

use Rumi\Database\Drivers\DatabaseDriver;
use Rumi\Database\Drivers\PdoDriver;

class DatabaseServiceProvider  implements  ServiceProvider{

  public function registerServices(){
    match(config("database.connection", "mysql")){
      "mysql", "mariadb" => singleton(DatabaseDriver::class, fn() => new PdoDriver()),
    };

  }
}